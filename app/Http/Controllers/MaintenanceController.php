<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\MaintenanceIssue;
use App\Models\Room;
use App\Models\User;
use App\Services\BookingAvailability;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    public function index(Request $request): View
    {
        $user = $this->ensureStaff($request);

        $validated = $request->validate([
            'status' => [
                'nullable',
                'in:open,in_progress,resolved',
            ],
        ]);

        $status = $validated['status'] ?? '';

        $query = MaintenanceIssue::with(['room', 'creator'])
            ->latest();

        if ($status !== '') {
            $query->where('status', $status);
        }

        /** @var LengthAwarePaginator<int, MaintenanceIssue> $issues */
        $issues = $query->paginate(10)->withQueryString();

        $rooms = Room::orderBy('number')->get();

        $canManage = $user->canManageBookings();

        return view(
            'maintenance.index',
            compact('issues', 'rooms', 'status', 'canManage')
        );
    }

    public function store(
        Request $request,
        BookingAvailability $availability
    ): RedirectResponse {
        $user = $this->ensureStaff($request);

        $data = $request->validate([
            'room_id' => [
                'required',
                'integer',
                'exists:rooms,id',
            ],
            'title' => [
                'required',
                'string',
                'max:150',
            ],
            'description' => [
                'nullable',
                'string',
                'max:3000',
            ],
            'starts_on' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:today',
            ],
            'ends_on' => [
                'required',
                'date_format:Y-m-d',
                'after:starts_on',
            ],
            'blocks_booking' => [
                'required',
                'boolean',
            ],
        ]);

        $blocksBooking = $request->boolean('blocks_booking');

        if ($blocksBooking) {
            abort_unless(
                $user->canManageBookings(),
                403,
                'Only an admin or manager can block a room.'
            );
        }

        DB::transaction(function () use (
            $data,
            $blocksBooking,
            $user,
            $availability
        ): void {
            $room = $availability->lockRoom(
                (int) $data['room_id']
            );

            if ($blocksBooking) {
                $this->ensureNoBookingConflict(
                    (int) $room->id,
                    $data['starts_on'],
                    $data['ends_on']
                );
            }

            MaintenanceIssue::create([
                'room_id' => $room->id,
                'created_by' => $user->id,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'status' => 'open',
                'blocks_booking' => $blocksBooking,
                'starts_on' => $data['starts_on'],
                'ends_on' => $data['ends_on'],
            ]);
        }, 5);

        return redirect()
            ->route('maintenance.index')
            ->with('success', 'Maintenance issue recorded.');
    }

    public function update(
        Request $request,
        MaintenanceIssue $issue,
        BookingAvailability $availability
    ): RedirectResponse {
        $user = $this->ensureStaff($request);

        abort_unless(
            $user->canManageBookings(),
            403,
            'Only an admin or manager can update maintenance issues.'
        );

        $data = $request->validate([
            'title' => [
                'required',
                'string',
                'max:150',
            ],
            'description' => [
                'nullable',
                'string',
                'max:3000',
            ],
            'status' => [
                'required',
                'in:open,in_progress,resolved',
            ],
            'starts_on' => [
                'required',
                'date_format:Y-m-d',
            ],
            'ends_on' => [
                'required',
                'date_format:Y-m-d',
                'after:starts_on',
            ],
            'blocks_booking' => [
                'required',
                'boolean',
            ],
        ]);

        $blocksBooking = $request->boolean('blocks_booking');

        DB::transaction(function () use (
            $issue,
            $data,
            $blocksBooking,
            $availability
        ): void {
            $availability->lockRoom(
                (int) $issue->room_id
            );

            $current = MaintenanceIssue::whereKey($issue->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                $current->status === 'resolved'
                && $data['status'] !== 'resolved'
            ) {
                throw ValidationException::withMessages([
                    'maintenance' => 'Resolved issues cannot be reopened. Create a new issue if further work is needed.',
                ]);
            }

            if (
                $blocksBooking
                && $data['status'] !== 'resolved'
            ) {
                $this->ensureNoBookingConflict(
                    (int) $current->room_id,
                    $data['starts_on'],
                    $data['ends_on']
                );
            }

            $current->update([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'status' => $data['status'],
                'starts_on' => $data['starts_on'],
                'ends_on' => $data['ends_on'],
                'blocks_booking' => $blocksBooking,
                'resolved_at' => $data['status'] === 'resolved'
                    ? ($current->resolved_at ?? now())
                    : null,
            ]);
        }, 5);

        return redirect()
            ->route('maintenance.index')
            ->with('success', 'Maintenance issue updated.');
    }

    private function ensureNoBookingConflict(
        int $roomId,
        string $startsOn,
        string $endsOn
    ): void {
        $booking = Booking::where('room_id', $roomId)
            ->whereIn(
                'status',
                ['pending', 'confirmed', 'checked_in']
            )
            ->where('check_in', '<', $endsOn)
            ->where('check_out', '>', $startsOn)
            ->lockForUpdate()
            ->first();

        if ($booking) {
            throw ValidationException::withMessages([
                'maintenance' => 'These maintenance dates overlap an active booking. Choose different dates or resolve the reservation conflict first.',
            ]);
        }
    }

    private function ensureStaff(Request $request): User
    {
        $user = $request->user();

        if (
            !$user instanceof User
            || !$user->canViewBookings()
        ) {
            abort(403);
        }

        return $user;
    }
}