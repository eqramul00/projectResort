<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;

class BookingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canViewBookings();
    }

    public function view(User $user, Booking $booking): bool
    {
        return $user->canViewBookings();
    }

    public function confirm(User $user, Booking $booking): bool
    {
        return $user->canManageBookings();
    }

    public function cancel(User $user, Booking $booking): bool
    {
        return $user->canManageBookings();
    }

    public function checkIn(User $user, Booking $booking): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'reception']);
    }

    public function checkOut(User $user, Booking $booking): bool
    {
        return $user->hasAnyRole(['admin', 'manager', 'reception']);
    }
}