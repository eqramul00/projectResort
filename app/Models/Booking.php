<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class Booking extends Model
{
    protected $fillable = [
        'room_id',
        'guest_id',
        'check_in',
        'check_out',
        'status',
        'payment_method',
        'paid_amount',
        'transaction_id',
        'payment_description',
        'payment_receipt',
        'payment_status',
    ];

    protected function casts(): array
    {
        return [
            'agreed_rate' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'hold_expires_at' => 'datetime',
            'expired_at' => 'datetime',
            'actual_departed_at' => 'datetime',
            'checkout_completed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Booking $booking): void {
            $room = Room::findOrFail($booking->room_id);

            if (!is_numeric($room->price) || $room->price < 0) {
                throw ValidationException::withMessages([
                    'room_id' => 'This room does not have a valid price.',
                ]);
            }

            $booking->agreed_rate = $room->price;

            if (($booking->status ?? 'pending') === 'pending') {
                $expiresAt = Carbon::now();

                $expiresAt->addHours(
                    (int) config('hotel.booking_hold_hours', 24)
                );

                $booking->hold_expires_at = $expiresAt;
            }
        });

        static::updating(function (Booking $booking): void {
            if ($booking->isDirty('agreed_rate')) {
                throw ValidationException::withMessages([
                    'agreed_rate' => 'The agreed nightly rate cannot be changed through a normal booking update.',
                ]);
            }

            $paymentFields = [
                'paid_amount',
                'payment_method',
                'transaction_id',
                'payment_description',
                'payment_receipt',
                'payment_status',
            ];

            if (
                $booking->isDirty($paymentFields)
                && $booking->stayPayments()->exists()
            ) {
                throw ValidationException::withMessages([
                    'payment' => 'Additional stay payments already exist. Use Stay Management instead of replacing the original payment.',
                ]);
            }
        });
    }

    /** @return BelongsTo<Room, $this> */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /** @return BelongsTo<Guest, $this> */
    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    /** @return HasMany<BookingStayPayment, $this> */
    public function stayPayments(): HasMany
    {
        return $this->hasMany(BookingStayPayment::class);
    }
}