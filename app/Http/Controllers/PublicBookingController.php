<?php

namespace App\Http\Controllers;

use App\Mail\BookingReceiptMail;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PublicBookingController extends Controller
{
    public function welcome()
    {
        $rooms = Room::with(['images', 'bookings'])
            ->orderBy('number')
            ->take(6)
            ->get();

        return view('welcome', compact('rooms'));
    }

    public function create()
    {
        $rooms = Room::with(['images', 'bookings'])
            ->where('status', 'available')
            ->orderBy('number')
            ->get();

        return view('public.book', compact('rooms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'check_in' => ['required', 'date'],
            'check_out' => ['required', 'date', 'after:check_in'],
        ]);

        $hasConflict = Booking::where('room_id', $data['room_id'])
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($data) {
                $query->where('check_in', '<', $data['check_out'])
                    ->where('check_out', '>', $data['check_in']);
            })
            ->exists();

        if ($hasConflict) {
            return redirect()
                ->route('public.book')
                ->withInput()
                ->with('error', 'This room is already booked for the selected dates.');
        }

        $guest = Guest::firstOrCreate(
            ['phone' => $data['phone']],
            [
                'full_name' => $data['name'],
                'email' => $data['email'],
            ]
        );

        $guest->update([
            'full_name' => $data['name'],
            'email' => $data['email'],
        ]);

        $booking = Booking::create([
            'room_id' => $data['room_id'],
            'guest_id' => $guest->id,
            'check_in' => $data['check_in'],
            'check_out' => $data['check_out'],
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        try {
            Mail::to($guest->email)->send(new BookingReceiptMail($booking));
        } catch (\Exception $e) {
            // ignore mail failure
        }

        return redirect()
            ->route('public.book.payment', $booking)
            ->with('success', 'Booking request sent successfully. You can now pay online.');
    }

    public function paymentPage(Booking $booking)
    {
        $booking->load(['room', 'guest']);

        return view('public.payment', compact('booking'));
    }

    public function submitPayment(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'payment_method' => ['required', 'in:bkash,nagad,rocket'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'transaction_id' => ['nullable', 'string', 'max:100'],
            'payment_description' => ['nullable', 'string'],
            'payment_receipt' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $receiptPath = $booking->payment_receipt;

        if ($request->hasFile('payment_receipt')) {
            $receiptPath = $request->file('payment_receipt')->store('payment_receipts', 'public');
        }

        $booking->update([
            'payment_method' => $validated['payment_method'],
            'paid_amount' => $validated['paid_amount'],
            'transaction_id' => $validated['transaction_id'] ?? null,
            'payment_description' => $validated['payment_description'] ?? null,
            'payment_receipt' => $receiptPath,
            'payment_status' => 'submitted',
        ]);

        return redirect()
               ->route('public.book.payment', $booking)
               ->with('success', 'Payment submitted successfully. We will confirm your booking soon.');
    }
}