<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }
}