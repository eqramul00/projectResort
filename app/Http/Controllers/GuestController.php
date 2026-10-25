<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        $this->ensureStaff($request);

        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:150'],
        ]);

        $search = trim($validated['search'] ?? '');

        $query = Guest::with([
            'bookings' => function ($query) {
                $query->with('room')->orderByDesc('id');
            },
        ])->withCount('bookings');

        if ($search !== '') {
            $query->where(function ($query) use ($search) {
                $query->where('phone', 'like', '%' . $search . '%')
                    ->orWhere('full_name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $guests = $query->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('guests.index', compact('guests', 'search'));
    }

    public function create(Request $request)
    {
        $this->ensureStaff($request);

        return view('guests.create');
    }

    public function store(Request $request)
    {
        $this->ensureStaff($request);

        Guest::create($this->validateGuest($request));

        return redirect()
            ->route('guests.index')
            ->with('success', 'Guest profile created successfully.');
    }

    public function show(Request $request, Guest $guest)
    {
        $this->ensureStaff($request);

        $guest->load('user')->loadCount('bookings');

        $bookings = $guest->bookings()
            ->with('room')
            ->latest('id')
            ->paginate(10);

        return view('guests.show', compact('guest', 'bookings'));
    }

    public function edit(Request $request, Guest $guest)
    {
        $this->ensureStaff($request);

        return view('guests.edit', compact('guest'));
    }

    public function update(Request $request, Guest $guest)
    {
        $this->ensureStaff($request);

        $data = $this->validateGuest($request);

        DB::transaction(function () use ($guest, $data) {
            $current = Guest::whereKey($guest->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Contact details only. Never change account ownership here.
            $current->update($data);
        }, 5);

        return redirect()
            ->route('guests.show', $guest)
            ->with('success', 'Guest contact details updated.');
    }

    public function destroy(Request $request, Guest $guest)
    {
        $this->ensureStaff($request);

        abort_unless(
            $request->user()->role === 'admin',
            403,
            'Only administrators can delete guest profiles.'
        );

        DB::transaction(function () use ($guest) {
            $current = Guest::whereKey($guest->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($current->user_id !== null) {
                throw ValidationException::withMessages([
                    'guest' => 'This profile belongs to a guest login account and cannot be deleted from this page.',
                ]);
            }

            if ($current->bookings()->lockForUpdate()->first()) {
                throw ValidationException::withMessages([
                    'guest' => 'This guest has booking records. Keep the profile to preserve their booking and payment history.',
                ]);
            }

            $current->delete();
        }, 5);

        return redirect()
            ->route('guests.index')
            ->with('success', 'Guest profile deleted.');
    }

    private function validateGuest(Request $request): array
    {
        return $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
        ]);
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