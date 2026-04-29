<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRooms = Room::count();
        $availableRooms = Room::where('status', 'available')->count();
        $occupiedRooms = Room::where('status', 'occupied')->count();
        $maintenanceRooms = Room::where('status', 'maintenance')->count();

        $totalGuests = Guest::count();

        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $checkedInBookings = Booking::where('status', 'checked_in')->count();
        $checkedOutBookings = Booking::where('status', 'checked_out')->count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();

        $latestBookings = Booking::with(['room', 'guest'])
            ->latest()
            ->take(6)
            ->get();

        $latestGuests = Guest::latest()
            ->take(6)
            ->get();

        $totalUsers = User::count();

        return view('dashboard', compact(
            'totalRooms',
            'availableRooms',
            'occupiedRooms',
            'maintenanceRooms',
            'totalGuests',
            'totalBookings',
            'pendingBookings',
            'confirmedBookings',
            'checkedInBookings',
            'checkedOutBookings',
            'cancelledBookings',
            'latestBookings',
            'latestGuests',
            'totalUsers'
        ));
    }
}