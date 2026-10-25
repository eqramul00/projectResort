<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\MaintenanceIssue;
use App\Models\Room;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingAvailability
{
    public function lockRoom(int $roomId): Room
    {
        return Room::whereKey($roomId)
            ->lockForUpdate()
            ->firstOrFail();
    }

    /**
     * Call this inside a transaction after locking the room.
     */
    public function expireRoomHolds(int $roomId): int
    {
        $timestamp = now();

        return Booking::where('room_id', $roomId)
            ->where('status', 'pending')
            ->where('payment_status', 'pending')
            ->whereNotNull('hold_expires_at')
            ->where('hold_expires_at', '<=', $timestamp)
            ->where(function ($query) {
                $query->whereNull('paid_amount')
                    ->orWhere('paid_amount', '<=', 0);
            })
            ->where(function ($query) {
                $query->whereNull('payment_receipt')
                    ->orWhere('payment_receipt', '');
            })
            ->update([
                'status' => 'cancelled',
                'expired_at' => $timestamp,
                'updated_at' => $timestamp,
            ]);
    }

    public function ensureAvailable(
        Room $room,
        string $checkIn,
        string $checkOut,
        ?int $ignoreBookingId = null
    ): void {
        $this->expireRoomHolds($room->id);

        if (!in_array($room->status, ['available', 'occupied'], true)) {
            throw ValidationException::withMessages([
                'room_id' => 'This room is currently unavailable or under maintenance.',
            ]);
        }

        $maintenance = MaintenanceIssue::where('room_id', $room->id)
            ->where('blocks_booking', true)
            ->whereIn('status', ['open', 'in_progress'])
            ->where('starts_on', '<', $checkOut)
            ->where('ends_on', '>', $checkIn)
            ->lockForUpdate()
            ->first();

        if ($maintenance) {
            throw ValidationException::withMessages([
                'room_id' => 'This room is blocked for maintenance during part of your selected stay.',
            ]);
        }

        $query = Booking::where('room_id', $room->id)
            ->whereIn('status', [
                'pending',
                'confirmed',
                'checked_in',
            ])
            ->where('check_in', '<', $checkOut)
            ->where('check_out', '>', $checkIn);

        if ($ignoreBookingId !== null) {
            $query->where('id', '!=', $ignoreBookingId);
        }

        $conflict = $query->lockForUpdate()->first();

        if ($conflict) {
            throw ValidationException::withMessages([
                'room_id' => 'This room is already booked for the selected dates.',
            ]);
        }
    }

    public function withLockedBooking(
        Booking $booking,
        Closure $callback,
        ?int $newRoomId = null
    ): mixed {
        return DB::transaction(function () use (
            $booking,
            $callback,
            $newRoomId
        ) {
            $roomIds = [(int) $booking->room_id];

            if ($newRoomId !== null) {
                $roomIds[] = $newRoomId;
            }

            $roomIds = array_values(array_unique($roomIds));
            sort($roomIds, SORT_NUMERIC);

            $rooms = [];

            foreach ($roomIds as $roomId) {
                $rooms[$roomId] = $this->lockRoom($roomId);
                $this->expireRoomHolds($roomId);
            }

            $current = Booking::whereKey($booking->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!isset($rooms[(int) $current->room_id])) {
                throw ValidationException::withMessages([
                    'booking' => 'This booking changed. Refresh the page and try again.',
                ]);
            }

            return $callback($current, $rooms);
        }, 5);
    }
}