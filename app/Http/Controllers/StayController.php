<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\BookingAvailability;
use App\Services\StayManagement;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StayController extends Controller
{
    public function index(
        Request $request,
        StayManagement $management
    ) {
        $this->ensureStaff($request);

        $data = $request->validate([
            'booking' => [
                'nullable',
                'integer',
                'exists:bookings,id',
            ],
        ]);

        $stays = Booking::with(['room', 'guest'])
            ->where('status', 'checked_in')
            ->orderBy('check_out')
            ->get();

        $booking = !empty($data['booking'])
            ? Booking::with(['room', 'guest'])
                ->findOrFail($data['booking'])
            : null;

        $summary = $booking
            ? $management->summary($booking)
            : null;

        $payments = $booking
            ? $booking->stayPayments()
                ->with('recorder')
                ->latest()
                ->get()
            : collect();

        return view('bookings.stay_management', compact(
            'stays',
            'booking',
            'summary',
            'payments'
        ));
    }

    public function update(
        Request $request,
        Booking $booking,
        BookingAvailability $availability,
        StayManagement $management
    ) {
        $this->ensureStaff($request);

        $data = $request->validate([
            'action' => [
                'required',
                'in:extension,payment,departure',
            ],

            'new_checkout' => [
                'exclude_unless:action,extension',
                'required',
                'date_format:Y-m-d',
            ],

            'expected_checkout' => [
                'exclude_unless:action,extension',
                'required',
                'date_format:Y-m-d',
            ],

            'operation_key' => [
                'exclude_if:action,departure',
                'required',
                'uuid',
            ],

            'amount' => [
                'exclude_if:action,departure',
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
                'decimal:0,2',
            ],

            'payment_method' => [
                'exclude_if:action,departure',
                'required',
                'in:cash,bank,bkash,nagad,rocket',
            ],

            'transaction_id' => [
                'exclude_if:action,departure',
                'nullable',
                'string',
                'max:100',
            ],

            'notes' => [
                'exclude_if:action,departure',
                'nullable',
                'string',
                'max:2000',
            ],

            'payment_verified' => [
                'exclude_if:action,departure',
                'required',
                'accepted',
            ],

            'departure_confirmed' => [
                'exclude_unless:action,departure',
                'required',
                'accepted',
            ],
        ]);

        if (in_array($data['action'], ['extension', 'payment'], true)) {
            abort_unless(
                $request->user()->canManageBookings(),
                403,
                'Only admins and managers can record stay payments.'
            );
        } else {
            $this->authorize('checkOut', $booking);
        }

        try {
            $availability->withLockedBooking(
                $booking,
                function (Booking $current, array $rooms) use (
                    $data,
                    $request,
                    $availability,
                    $management
                ) {
                    $room = $rooms[(int) $current->room_id];

                    if ($data['action'] === 'extension') {
                        $management->extend(
                            $current,
                            $room,
                            $data,
                            $request->user()->id,
                            $availability
                        );
                    } elseif ($data['action'] === 'payment') {
                        $management->settle(
                            $current,
                            $data,
                            $request->user()->id
                        );
                    } else {
                        $management->recordDeparture($current, $room);
                    }
                }
            );
        } catch (ValidationException $exception) {
            return redirect()
                ->route('stays.index', ['booking' => $booking->id])
                ->withErrors($exception->errors());
        }

        $message = match ($data['action']) {
            'extension' => 'Payment recorded and stay extended.',
            'payment' => 'Additional verified payment recorded.',
            default => 'Actual departure recorded. Financial checkout remains separate.',
        };

        return redirect()
            ->route('stays.index', ['booking' => $booking->id])
            ->with('success', $message);
    }

    public function checkout(
        Request $request,
        Booking $booking,
        BookingAvailability $availability,
        StayManagement $management
    ) {
        $this->ensureStaff($request);
        $this->authorize('checkOut', $booking);

        try {
            $availability->withLockedBooking(
                $booking,
                function (Booking $current, array $rooms) use ($management) {
                    $management->completeCheckout(
                        $current,
                        $rooms[(int) $current->room_id]
                    );
                }
            );
        } catch (ValidationException $exception) {
            return redirect()
                ->route('stays.index', ['booking' => $booking->id])
                ->withErrors($exception->errors());
        }

        return redirect()
            ->route('stays.index', ['booking' => $booking->id])
            ->with('success', 'Checkout completed successfully.');
    }

    private function ensureStaff(Request $request): void
    {
        abort_unless(
            $request->user()
                && $request->user()->canViewBookings(),
            403
        );
    }
}