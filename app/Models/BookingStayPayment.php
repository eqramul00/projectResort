<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingStayPayment extends Model
{
    protected $fillable = [
        'booking_id',
        'recorded_by',
        'operation_key',
        'type',
        'previous_check_out',
        'new_check_out',
        'extra_nights',
        'nightly_rate',
        'amount',
        'payment_method',
        'transaction_id',
        'notes',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'previous_check_out' => 'date',
            'new_check_out' => 'date',
            'extra_nights' => 'integer',
            'nightly_rate' => 'decimal:2',
            'amount' => 'decimal:2',
            'verified_at' => 'datetime',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}