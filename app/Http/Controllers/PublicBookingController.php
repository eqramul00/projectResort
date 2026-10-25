<?php

namespace App\Http\Controllers;

use App\Mail\BookingReceiptMail;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use App\Services\BookingAvailability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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

    public function create(Request $request)
    {
        // A room parameter opens the separate booking-details page.
        if ($request->has('room')) {
            $requestedRoom = $request->query('room');

            abort_unless(
                is_scalar($requestedRoom)
                    && filter_var(
                        $requestedRoom,
                        FILTER_VALIDATE_INT
                    ) !== false
                    && (int) $requestedRoom > 0,
                404
            );

            $selectedRoom = Room::with('images')
                ->whereIn('status', ['available', 'occupied'])
                ->where('price', '>=', 0)
                ->findOrFail((int) $requestedRoom);

            return view(
                'public.booking_details',
                compact('selectedRoom')
            );
        }

        $rooms = Room::with('images')
            ->whereIn('status', ['available', 'occupied'])
            ->where('price', '>=', 0)
            ->orderBy('number')
            ->get();

        return view('public.book', compact('rooms'));
    }

    public function store(
        Request $request,
        BookingAvailability $availability
    ) {
        $user = $request->user();
        $isGuestAccount = $user && $user->isGuest();

        $rules = [
            'room_id' => [
                'required',
                'integer',
                'exists:rooms,id',
            ],
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
        ];

        if (!$isGuestAccount) {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['phone'] = ['required', 'string', 'max:50'];
            $rules['email'] = ['required', 'email', 'max:255'];
        }

        $data = $request->validate($rules);

        $booking = DB::transaction(function () use (
            $data,
            $user,
            $isGuestAccount,
            $availability
        ) {
            $room = $availability->lockRoom(
                (int) $data['room_id']
            );

            $availability->ensureAvailable(
                $room,
                $data['check_in'],
                $data['check_out']
            );

            if ($isGuestAccount) {
                $guest = $user->guest()
                    ->lockForUpdate()
                    ->first();

                if (!$guest) {
                    throw ValidationException::withMessages([
                        'guest' => 'Your guest profile could not be found. Please contact reception.',
                    ]);
                }
            } else {
                $guest = Guest::create([
                    'full_name' => $data['name'],
                    'phone' => $data['phone'],
                    'email' => $data['email'],
                ]);
            }

            return Booking::create([
                'room_id' => $room->id,
                'guest_id' => $guest->id,
                'check_in' => $data['check_in'],
                'check_out' => $data['check_out'],
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);
        }, 5);

        $request->session()->put(
            'guest_booking_access.' . $booking->id,
            true
        );

        $booking->load(['room', 'guest']);

        try {
            if ($booking->guest && $booking->guest->email) {
                Mail::to($booking->guest->email)->queue(
                    new BookingReceiptMail($booking)
                );
            }
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()
                ->route('public.book.payment', $booking)
                ->with(
                    'warning',
                    'Your booking was saved, but the email could not be queued. You can still submit payment details.'
                );
        }

        return redirect()
            ->route('public.book.payment', $booking)
            ->with(
                'success',
                'Booking request received. You can now submit your payment details.'
            );
    }

    public function paymentPage(
        Request $request,
        Booking $booking
    ) {
        $this->ensureBookingAccess($request, $booking);

        $booking->load(['room', 'guest']);

        return view('public.payment', compact('booking'));
    }

    public function submitPayment(
        Request $request,
        Booking $booking,
        BookingAvailability $availability
    ) {
        $this->ensureBookingAccess($request, $booking);

        $validated = $request->validate([
            'payment_method' => [
                'required',
                'in:bkash,nagad,rocket',
            ],
            'paid_amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:99999999.99',
                'decimal:0,2',
            ],
            'transaction_id' => [
                'nullable',
                'string',
                'max:100',
            ],
            'payment_description' => [
                'nullable',
                'string',
                'max:2000',
            ],
            'payment_receipt' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
            ],
        ]);

        $receiptPath = $request->file('payment_receipt')
            ->store('payment_receipts', 'public');

        if (!$receiptPath) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'The receipt could not be uploaded. Please try again.'
                );
        }

        try {
            $availability->withLockedBooking(
                $booking,
                function (Booking $current) use (
                    $request,
                    $validated,
                    $receiptPath
                ) {
                    $this->ensureBookingAccess($request, $current);

                    if (
                        $current->status !== 'pending'
                        || !in_array(
                            $current->payment_status,
                            ['pending', 'rejected'],
                            true
                        )
                    ) {
                        throw ValidationException::withMessages([
                            'payment' => 'This booking cannot accept another payment submission.',
                        ]);
                    }

                    if (
                        $current->agreed_rate === null
                        || !is_numeric($current->agreed_rate)
                        || (float) $current->agreed_rate < 0
                    ) {
                        throw ValidationException::withMessages([
                            'payment' => 'The agreed rate requires staff review before payment can be submitted.',
                        ]);
                    }

                    $current->update([
                        'payment_method' => $validated['payment_method'],
                        'paid_amount' => $validated['paid_amount'],
                        'transaction_id' => $validated['transaction_id'] ?? null,
                        'payment_description' => $validated['payment_description'] ?? null,
                        'payment_receipt' => $receiptPath,
                        'payment_status' => 'submitted',
                    ]);
                }
            );
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($receiptPath);

            throw $exception;
        }

        return redirect()
            ->route('public.book.payment', $booking)
            ->with(
                'success',
                'Payment submitted successfully. Your payment is awaiting verification.'
            );
    }

    private function ensureBookingAccess(
        Request $request,
        Booking $booking
    ): void {
        $user = $request->user();

        $ownerUserId = $booking->guest()->value('user_id');

        if ($ownerUserId !== null) {
            abort_unless(
                $user
                    && $user->isGuest()
                    && (int) $ownerUserId === (int) $user->id,
                403,
                'You do not have access to this booking.'
            );

            return;
        }

        abort_unless(
            $request->session()->get(
                'guest_booking_access.' . $booking->id
            ) === true,
            403,
            'You do not have access to this booking.'
        );
    }
}