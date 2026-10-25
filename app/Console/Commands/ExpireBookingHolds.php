<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Room;
use App\Services\BookingAvailability;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExpireBookingHolds extends Command
{
    protected $signature = 'bookings:expire';

    protected $description = 'Expire unpaid pending booking holds';

    public function handle(BookingAvailability $availability): int
    {
        $expiredCount = 0;

        Room::query()
            ->whereIn(
                'id',
                Booking::query()
                    ->select('room_id')
                    ->where('status', 'pending')
                    ->where('payment_status', 'pending')
                    ->whereNotNull('hold_expires_at')
                    ->where('hold_expires_at', '<=', now())
            )
            ->select('id')
            ->orderBy('id')
            ->chunkById(100, function ($rooms) use (
                $availability,
                &$expiredCount
            ) {
                foreach ($rooms as $room) {
                    $expiredCount += DB::transaction(
                        function () use ($room, $availability) {
                            $availability->lockRoom($room->id);

                            return $availability->expireRoomHolds(
                                $room->id
                            );
                        },
                        5
                    );
                }
            });

        $this->info(
            $expiredCount . ' unpaid booking hold(s) expired.'
        );

        return self::SUCCESS;
    }
}