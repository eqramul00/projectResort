<?php

namespace App\Http\Controllers;

use App\Mail\BookingReceiptMail;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use App\Services\BookingAvailability;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Booking::class);

        $query = Booking::with(['room', 'guest']);
        $calendarQuery = Booking::with(['room', 'guest'])
            ->orderBy('check_in');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
            $calendarQuery->where('status', $request->status);
        }

        $bookings = $query->latest()->get();
        $calendarBookings = $calendarQuery->get();

        return view(
            'bookings.index',
            compact('bookings', 'calendarBookings')
        );
    }

    public function calendar()
    {
        $this->authorize('viewAny', Booking::class);

        $bookings = Booking::with(['room', 'guest'])
            ->orderBy('check_in')
            ->get()
            ->groupBy('check_in');

        return view('bookings.calendar', compact('bookings'));
    }

    public function create()
    {
        $this->ensureManager();

        $rooms = Room::orderBy('number')->get();
        $guests = Guest::orderBy('full_name')->get();

        return view('bookings.create', compact('rooms', 'guests'));
    }

    public function store(
        Request $request,
        BookingAvailability $availability
    ) {
        $this->ensureManager();

        $data = $request->validate([
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'guest_id' => ['required', 'integer', 'exists:guests,id'],
            'check_in' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:today',
            ],
            'check_out' => [
                'required',
                'date_format:Y-m-d',
                'after:check_in',
            ],
            'status' => ['sometimes', 'in:pending'],
        ]);

        $booking = DB::transaction(function () use (
            $data,
            $availability
        ) {
            $room = $availability->lockRoom((int) $data['room_id']);

            $availability->ensureAvailable(
                $room,
                $data['check_in'],
                $data['check_out']
            );

            return Booking::create([
                'room_id' => $room->id,
                'guest_id' => $data['guest_id'],
                'check_in' => $data['check_in'],
                'check_out' => $data['check_out'],
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);
        }, 5);

        return redirect()
            ->route('bookings.show', $booking)
            ->with('success', 'Booking created successfully.');
    }

    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);

        $booking->load(['room', 'guest']);

        return view('bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $this->ensureManager();

        $rooms = Room::orderBy('number')->get();
        $guests = Guest::orderBy('full_name')->get();

        return view(
            'bookings.edit',
            compact('booking', 'rooms', 'guests')
        );
    }

    public function update(
        Request $request,
        Booking $booking,
        BookingAvailability $availability
    ) {
        $this->ensureManager();

        $data = $request->validate([
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'guest_id' => ['required', 'integer', 'exists:guests,id'],
            'check_in' => ['required', 'date_format:Y-m-d'],
            'check_out' => [
                'required',
                'date_format:Y-m-d',
                'after:check_in',
            ],
            'status' => [
                'sometimes',
                'in:pending,confirmed,checked_in,checked_out,cancelled',
            ],
        ]);

        $availability->withLockedBooking(
            $booking,
            function (Booking $current, array $rooms) use (
                $data,
                $availability
            ) {
                if (!in_array($current->status, ['pending', 'confirmed'], true)) {
                    $this->bookingError(
                        'Only pending or confirmed bookings can be edited.'
                    );
                }

                if (
                    isset($data['status'])
                    && $data['status'] !== $current->status
                ) {
                    $this->bookingError(
                        'Use the Confirm, Check In, Check Out or Cancel buttons to change booking status.'
                    );
                }

                // Changing a paid booking's guest needs a separate audited flow.
                if (
                    $current->payment_status !== 'pending'
                    && (int) $data['guest_id'] !== (int) $current->guest_id
                ) {
                    $this->bookingError(
                        'The guest cannot be changed after payment details have been recorded.'
                    );
                }

                $room = $rooms[(int) $data['room_id']];

                $availability->ensureAvailable(
                    $room,
                    $data['check_in'],
                    $data['check_out'],
                    $current->id
                );

                $current->update([
                    'room_id' => $data['room_id'],
                    'guest_id' => $data['guest_id'],
                    'check_in' => $data['check_in'],
                    'check_out' => $data['check_out'],
                ]);
            },
            (int) $data['room_id']
        );

        return redirect()
            ->route('bookings.index')
            ->with('success', 'Booking updated successfully.');
    }

    public function destroy(
        Booking $booking,
        BookingAvailability $availability
    ) {
        $this->ensureManager();

        $availability->withLockedBooking(
            $booking,
            function (Booking $current) {
                if (
                    $current->status !== 'cancelled'
                    || $current->payment_status !== 'pending'
                    || (float) $current->paid_amount > 0
                    || $current->payment_receipt
                ) {
                    $this->bookingError(
                        'Only cancelled bookings with no recorded payment or receipt can be deleted.'
                    );
                }

                $current->delete();
            }
        );

        return redirect()
            ->route('bookings.index')
            ->with('success', 'Booking deleted successfully.');
    }

    public function manualPaymentPage(Booking $booking)
    {
        $this->ensureManager();

        $booking->load(['room', 'guest']);

        return view('bookings.manual_payment', compact('booking'));
    }

    public function manualPaymentSubmit(
        Request $request,
        Booking $booking,
        BookingAvailability $availability
    ) {
        $this->ensureManager();

        $validated = $request->validate([
            'payment_method' => [
                'required',
                'in:bkash,nagad,rocket,cash,bank',
            ],
            'paid_amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:99999999.99',
                'decimal:0,2',
            ],
            'transaction_id' => ['nullable', 'string', 'max:100'],
            'payment_description' => ['nullable', 'string', 'max:2000'],
        ]);

        $availability->withLockedBooking(
            $booking,
            function (Booking $current) use ($validated) {
                if ($current->status === 'cancelled') {
                    $this->bookingError(
                        'Payments cannot be added to a cancelled booking.'
                    );
                }

                $current->update([
                    'payment_method' => $validated['payment_method'],
                    'paid_amount' => $validated['paid_amount'],
                    'transaction_id' => $validated['transaction_id'] ?? null,
                    'payment_description' => $validated['payment_description'] ?? null,
                    'payment_status' => 'verified',
                ]);
            }
        );

        return redirect()
            ->route('bookings.show', $booking)
            ->with('success', 'Payment saved successfully.');
    }

    public function verifyPayment(
        Booking $booking,
        BookingAvailability $availability
    ) {
        $this->authorize('confirm', $booking);

        $availability->withLockedBooking(
            $booking,
            function (Booking $current) {
                if (
                    $current->status === 'cancelled'
                    || $current->payment_status !== 'submitted'
                ) {
                    $this->bookingError(
                        'Only submitted payments on an active booking can be verified.'
                    );
                }

                $current->update(['payment_status' => 'verified']);
            }
        );

        return back()->with('success', 'Payment verified successfully.');
    }

    public function rejectPayment(
        Booking $booking,
        BookingAvailability $availability
    ) {
        $this->authorize('confirm', $booking);

        $availability->withLockedBooking(
            $booking,
            function (Booking $current) {
                if ($current->payment_status !== 'submitted') {
                    $this->bookingError(
                        'Only submitted payments can be rejected.'
                    );
                }

                $current->update(['payment_status' => 'rejected']);
            }
        );

        return back()->with('success', 'Payment rejected successfully.');
    }

    public function confirm(
        Booking $booking,
        BookingAvailability $availability
    ) {
        $this->authorize('confirm', $booking);

        $current = $availability->withLockedBooking(
            $booking,
            function (Booking $current, array $rooms) use ($availability) {
                if ($current->status !== 'pending') {
                    $this->bookingError(
                        'Only pending bookings can be confirmed.'
                    );
                }

                if ($current->payment_status !== 'verified') {
                    $this->bookingError(
                        'Payment must be verified before confirming booking.'
                    );
                }

                $availability->ensureAvailable(
                    $rooms[(int) $current->room_id],
                    $current->check_in,
                    $current->check_out,
                    $current->id
                );

                $current->update(['status' => 'confirmed']);

                return $current;
            }
        );

        $current->load(['room', 'guest']);

        try {
            if ($current->guest && $current->guest->email) {
                Mail::to($current->guest->email)->queue(
                    new BookingReceiptMail($current)
                );

                return back()->with(
                    'success',
                    'Booking confirmed successfully. Receipt email queued.'
                );
            }
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with(
                'warning',
                'Booking confirmed, but the receipt email could not be queued.'
            );
        }

        return back()->with('success', 'Booking confirmed successfully.');
    }

    public function checkIn(
        Booking $booking,
        BookingAvailability $availability
    ) {
        $this->authorize('checkIn', $booking);

        $availability->withLockedBooking(
            $booking,
            function (Booking $current, array $rooms) use ($availability) {
                if ($current->status !== 'confirmed') {
                    $this->bookingError(
                        'Only confirmed bookings can be checked in.'
                    );
                }

                $today = now()->toDateString();

                if (
                    $today < $current->check_in
                    || $today >= $current->check_out
                ) {
                    $this->bookingError(
                        'Check-in must be within the booked stay dates. Edit the dates first if necessary.'
                    );
                }

                $room = $rooms[(int) $current->room_id];

                if ($room->status !== 'available') {
                    $this->bookingError(
                        'The room must be available before check-in.'
                    );
                }

                $availability->ensureAvailable(
                    $room,
                    $current->check_in,
                    $current->check_out,
                    $current->id
                );

                $current->update(['status' => 'checked_in']);
                $room->update(['status' => 'occupied']);
            }
        );

        return back()->with('success', 'Guest checked in successfully.');
    }

    public function checkOut(
        Booking $booking,
        BookingAvailability $availability
    ) {
        $this->authorize('checkOut', $booking);

        $availability->withLockedBooking(
            $booking,
            function (Booking $current, array $rooms) {
                if ($current->status !== 'checked_in') {
                    $this->bookingError(
                        'Only checked-in bookings can be checked out.'
                    );
                }

                $current->update(['status' => 'checked_out']);

                $room = $rooms[(int) $current->room_id];

                // Do not remove a maintenance flag during checkout.
                if ($room->status === 'occupied') {
                    $room->update(['status' => 'available']);
                }
            }
        );

        return back()->with('success', 'Guest checked out successfully.');
    }

    public function cancel(
        Booking $booking,
        BookingAvailability $availability
    ) {
        $this->authorize('cancel', $booking);

        $availability->withLockedBooking(
            $booking,
            function (Booking $current) {
                if (!in_array($current->status, ['pending', 'confirmed'], true)) {
                    $this->bookingError(
                        'Only pending or confirmed bookings can be cancelled. Use Check Out for a guest who has already checked in.'
                    );
                }

                $current->update(['status' => 'cancelled']);
            }
        );

        return back()->with('success', 'Booking cancelled successfully.');
    }

    public function invoice(Booking $booking)
    {
        $this->authorize('view', $booking);

        $booking->load(['room', 'guest']);

        $pdf = Pdf::loadView('bookings.invoice', compact('booking'));

        return $pdf->download(
            'booking-invoice-' . $booking->id . '.pdf'
        );
    }

    public function sendReceipt(Booking $booking)
    {
        $this->authorize('view', $booking);

        $booking->load(['room', 'guest']);

        if (!$booking->guest || !$booking->guest->email) {
            return back()->with('error', 'Guest email not found.');
        }

        try {
            Mail::to($booking->guest->email)->queue(
                new BookingReceiptMail($booking)
            );

            return back()->with(
                'success',
                'Booking receipt email queued successfully.'
            );
        } catch (\Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'The booking receipt email could not be queued.'
            );
        }
    }

   private function ensureManager(): void
    {
    $user = \Illuminate\Support\Facades\Auth::user();

    abort_unless(
        $user instanceof \App\Models\User
            && $user->canManageBookings(),
        403
        );
    }

    private function bookingError(string $message): never
    {
        throw ValidationException::withMessages([
            'booking' => $message,
        ]);
    }
}