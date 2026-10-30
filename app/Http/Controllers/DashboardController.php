<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use App\Models\User;
use App\Services\OccupancyForecastFile;

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

        $occupancyForecast = app(OccupancyForecastFile::class)->load();

        if ($occupancyForecast['ready']) {
            $dates = array_column(
                $occupancyForecast['days'],
                'date'
            );

            $onBooks = Booking::whereIn(
                'status',
                ['confirmed', 'checked_in']
            )
                ->where('check_in', '<=', $dates[6])
                ->where('check_out', '>', $dates[0])
                ->get([
                    'room_id',
                    'check_in',
                    'check_out',
                ]);

            foreach ($occupancyForecast['days'] as &$day) {
                $date = $day['date'];
                $confirmedRoomIds = [];

                foreach ($onBooks as $booking) {
                    $checkIn = substr(
                        (string) $booking->check_in,
                        0,
                        10
                    );

                    $checkOut = substr(
                        (string) $booking->check_out,
                        0,
                        10
                    );

                    if ($checkIn <= $date && $checkOut > $date) {
                        $confirmedRoomIds[(int) $booking->room_id] = true;
                    }
                }

                $day['confirmed_rooms'] = count($confirmedRoomIds);
            }

            unset($day);
        }

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
            'totalUsers',
            'occupancyForecast'
        ));
    }
}