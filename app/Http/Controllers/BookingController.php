<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use Illuminate\Http\Request;
use App\Mail\BookingReceiptMail;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Booking::class);

        $query = Booking::with(['room', 'guest']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->get();

        $calendarQuery = Booking::with(['room', 'guest'])->orderBy('check_in');

        if ($request->filled('status')) {
            $calendarQuery->where('status', $request->status);
        }

        $calendarBookings = $calendarQuery->get();

        return view('bookings.index', compact('bookings', 'calendarBookings'));
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
        $rooms = Room::orderBy('number')->get();
        $guests = Guest::orderBy('full_name')->get();

        return view('bookings.create', compact('rooms', 'guests'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'guest_id' => ['required', 'exists:guests,id'],
            'check_in' => ['required', 'date'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'status' => ['required', 'in:pending,confirmed,checked_in,checked_out,cancelled'],
        ]);

        $hasConflict = Booking::where('room_id', $data['room_id'])
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($data) {
                $query->where('check_in', '<', $data['check_out'])
                      ->where('check_out', '>', $data['check_in']);
            })
            ->exists();

        if ($hasConflict) {
            return back()
                ->withInput()
                ->with('error', 'This room is already booked for the selected dates.');
        }

        $booking = Booking::create($data);

        return redirect()
            ->route('bookings.show', $booking->id)
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
        $rooms = Room::orderBy('number')->get();
        $guests = Guest::orderBy('full_name')->get();

        return view('bookings.edit', compact('booking', 'rooms', 'guests'));
    }

    public function update(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'guest_id' => ['required', 'exists:guests,id'],
            'check_in' => ['required', 'date'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'status' => ['required', 'in:pending,confirmed,checked_in,checked_out,cancelled'],
        ]);

        $hasConflict = Booking::where('room_id', $data['room_id'])
            ->where('id', '!=', $booking->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($data) {
                $query->where('check_in', '<', $data['check_out'])
                      ->where('check_out', '>', $data['check_in']);
            })
            ->exists();

        if ($hasConflict) {
            return back()
                ->withInput()
                ->with('error', 'This room is already booked for the selected dates.');
        }

        $booking->update($data);

        return redirect()->route('bookings.index')->with('success', 'Booking updated successfully.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully.');
    }

    public function manualPaymentPage(Booking $booking)
    {
        $booking->load(['room', 'guest']);

        return view('bookings.manual_payment', compact('booking'));
    }

    public function manualPaymentSubmit(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'payment_method' => ['required', 'in:bkash,nagad,rocket,cash,bank'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'transaction_id' => ['nullable', 'string', 'max:100'],
            'payment_description' => ['nullable', 'string'],
        ]);

        $booking->update([
            'payment_method' => $validated['payment_method'],
            'paid_amount' => $validated['paid_amount'],
            'transaction_id' => $validated['transaction_id'] ?? null,
            'payment_description' => $validated['payment_description'] ?? null,
            'payment_status' => 'verified',
        ]);

        return redirect()
            ->route('bookings.show', $booking->id)
            ->with('success', 'Payment added successfully.');
    }

    public function verifyPayment(Booking $booking)
    {
        $this->authorize('confirm', $booking);

        if ($booking->payment_status !== 'submitted') {
            return back()->with('error', 'Only submitted payments can be verified.');
        }

        $booking->update([
            'payment_status' => 'verified',
        ]);

        return back()->with('success', 'Payment verified successfully.');
    }

    public function rejectPayment(Booking $booking)
    {
        $this->authorize('confirm', $booking);

        if (!in_array($booking->payment_status, ['submitted', 'verified'], true)) {
            return back()->with('error', 'Only submitted or verified payments can be rejected.');
        }

        $booking->update([
            'payment_status' => 'rejected',
        ]);

        return back()->with('success', 'Payment rejected successfully.');
    }

    public function confirm(Booking $booking)
    {
        $this->authorize('confirm', $booking);

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Only pending bookings can be confirmed.');
        }

        if ($booking->payment_status !== 'verified') {
            return back()->with('error', 'Payment must be verified before confirming booking.');
        }

        $booking->update([
            'status' => 'confirmed',
        ]);

        $booking->load(['room', 'guest']);

        try {
            if ($booking->guest && $booking->guest->email) {
                Mail::to($booking->guest->email)->send(new BookingReceiptMail($booking));
            }

            return back()->with('success', 'Booking confirmed successfully and receipt sent.');
        } catch (\Exception $e) {
            return back()->with('warning', 'Booking confirmed, but receipt email could not be sent.');
        }
    }

    public function checkIn(Booking $booking)
    {
        $this->authorize('checkIn', $booking);

        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'Only confirmed bookings can be checked in.');
        }

        $booking->update([
            'status' => 'checked_in',
        ]);

        $booking->room->update([
            'status' => 'occupied',
        ]);

        return back()->with('success', 'Guest checked in successfully.');
    }

    public function checkOut(Booking $booking)
    {
        $this->authorize('checkOut', $booking);

        if ($booking->status !== 'checked_in') {
            return back()->with('error', 'Only checked-in bookings can be checked out.');
        }

        $booking->update([
            'status' => 'checked_out',
        ]);

        $booking->room->update([
            'status' => 'available',
        ]);

        return back()->with('success', 'Guest checked out successfully.');
    }

    public function cancel(Booking $booking)
    {
        $this->authorize('cancel', $booking);

        if (in_array($booking->status, ['checked_out', 'cancelled'], true)) {
            return back()->with('error', 'This booking cannot be cancelled.');
        }

        $booking->update([
            'status' => 'cancelled',
        ]);

        if ($booking->room->status === 'occupied') {
            $booking->room->update([
                'status' => 'available',
            ]);
        }

        return back()->with('success', 'Booking cancelled successfully.');
    }

    public function invoice(Booking $booking)
    {
        $booking->load(['room', 'guest']);

        $pdf = Pdf::loadView('bookings.invoice', compact('booking'));

        return $pdf->download('booking-invoice-' . $booking->id . '.pdf');
    }

    public function sendReceipt(Booking $booking)
    {
        $booking->load(['room', 'guest']);

        if (!$booking->guest || !$booking->guest->email) {
            return back()->with('error', 'Guest email not found.');
        }

        try {
            Mail::to($booking->guest->email)->send(new BookingReceiptMail($booking));

            return back()->with('success', 'Booking receipt sent successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send booking receipt.');
        }
    }
}