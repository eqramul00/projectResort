<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->timestamp('hold_expires_at')->nullable();
            $table->timestamp('expired_at')->nullable();

            $table->index([
                'status',
                'payment_status',
                'hold_expires_at',
            ], 'bookings_hold_expiry_index');
        });

        // Give existing pending bookings a deadline based on creation time.
        DB::table('bookings')
            ->where('status', 'pending')
            ->orderBy('id')
            ->chunkById(200, function ($bookings) {
                foreach ($bookings as $booking) {
                    $createdAt = $booking->created_at
                        ? Carbon::parse($booking->created_at)
                        : Carbon::now();

                    DB::table('bookings')
                        ->where('id', $booking->id)
                        ->update([
                            'hold_expires_at' => $createdAt
                                ->copy()
                                ->addHours(24),
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex('bookings_hold_expiry_index');

            $table->dropColumn([
                'hold_expires_at',
                'expired_at',
            ]);
        });
    }
};