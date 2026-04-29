<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'address',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}