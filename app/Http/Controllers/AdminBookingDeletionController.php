<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\BookingAvailability;
use Illuminate\Http\Request;

class AdminBookingDeletionController extends Controller
{
    public function destroy(
        Request $request,
        Booking $booking,
        BookingAvailability $availability
    ) {
        abort_unless(
            $request->user()
                && $request->user()->role === 'admin',
            403,
            'Only administrators can delete bookings.'
        );

        $availability->withLockedBooking(
            $booking,
            function (Booking $current, array $rooms) {
                $room = $rooms[(int) $current->room_id];

                $wasCheckedIn = $current->status === 'checked_in';

                $current->delete();

                // Release an occupied room when its checked-in booking
                // is deleted, unless another checked-in booking remains.
                if ($wasCheckedIn && $room->status === 'occupied') {
                    $anotherStay = Booking::where('room_id', $room->id)
                        ->where('status', 'checked_in')
                        ->lockForUpdate()
                        ->first();

                    if (!$anotherStay) {
                        $room->update([
                            'status' => 'available',
                        ]);
                    }
                }
            }
        );

        return redirect()
            ->route('bookings.index')
            ->with(
                'success',
                'Booking and its recorded payment details deleted successfully.'
            );
    }
}