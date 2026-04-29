<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Expense;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FinanceController extends Controller
{
    private array $validStatuses = ['confirmed', 'checked_in', 'checked_out'];

    public function index(Request $request): View
    {
        // ✅ FIXED (no deprecated warning)
        $filter = $request->input('range', 'this_month');

        [$startDate, $endDate, $previousStartDate, $previousEndDate] = $this->getDateRange($filter);

        /*
        |--------------------------------------------------------------------------
        | BOOKINGS
        |--------------------------------------------------------------------------
        */

        $allBookings = Booking::with(['room', 'guest'])
            ->whereIn('status', $this->validStatuses)
            ->get();

        $filteredBookings = Booking::with(['room', 'guest'])
            ->whereIn('status', $this->validStatuses)
            ->whereDate('check_in', '>=', $startDate)
            ->whereDate('check_in', '<=', $endDate)
            ->get()
            ->map(function (Booking $booking) {
                $booking->nights = $this->calculateNights($booking->check_in, $booking->check_out);
                $booking->calculated_revenue = $this->calculateBookingRevenue($booking);
                return $booking;
            });

        $previousBookings = Booking::with('room')
            ->whereIn('status', $this->validStatuses)
            ->whereDate('check_in', '>=', $previousStartDate)
            ->whereDate('check_in', '<=', $previousEndDate)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | REVENUE
        |--------------------------------------------------------------------------
        */

        $totalRevenue = $this->calculateRevenue($allBookings);
        $rangeRevenue = $filteredBookings->sum('calculated_revenue');
        $previousRevenue = $this->calculateRevenue($previousBookings);

        /*
        |--------------------------------------------------------------------------
        | EXPENSES
        |--------------------------------------------------------------------------
        */

        $totalExpenses = Expense::sum('amount');

        $rangeExpenses = Expense::whereDate('expense_date', '>=', $startDate)
            ->whereDate('expense_date', '<=', $endDate)
            ->sum('amount');

        $previousExpenses = Expense::whereDate('expense_date', '>=', $previousStartDate)
            ->whereDate('expense_date', '<=', $previousEndDate)
            ->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | PROFIT
        |--------------------------------------------------------------------------
        */

        $netProfit = $totalRevenue - $totalExpenses;
        $rangeProfit = $rangeRevenue - $rangeExpenses;
        $previousProfit = $previousRevenue - $previousExpenses;

        /*
        |--------------------------------------------------------------------------
        | GROWTH
        |--------------------------------------------------------------------------
        */

        $revenueGrowth = $this->calculateGrowth($rangeRevenue, $previousRevenue);
        $expenseGrowth = $this->calculateGrowth($rangeExpenses, $previousExpenses);
        $profitGrowth = $this->calculateGrowth($rangeProfit, $previousProfit);

        /*
        |--------------------------------------------------------------------------
        | RECENT DATA
        |--------------------------------------------------------------------------
        */

        $recentExpenses = Expense::latest('expense_date')
            ->take(8)
            ->get();

        $recentRevenueBookings = Booking::with(['room', 'guest'])
            ->whereIn('status', $this->validStatuses)
            ->latest('created_at')
            ->take(8)
            ->get()
            ->map(function (Booking $booking) {
                $booking->nights = $this->calculateNights($booking->check_in, $booking->check_out);
                $booking->calculated_revenue = $this->calculateBookingRevenue($booking);
                return $booking;
            });

        /*
        |--------------------------------------------------------------------------
        | KPIs
        |--------------------------------------------------------------------------
        */

        $totalBookings = $filteredBookings->count();

        $averageBookingValue = $totalBookings > 0
            ? $rangeRevenue / $totalBookings
            : 0;

        $averageStayLength = $totalBookings > 0
            ? $filteredBookings->avg('nights')
            : 0;

        /*
        |--------------------------------------------------------------------------
        | OCCUPANCY
        |--------------------------------------------------------------------------
        */

        $occupiedRoomIds = Booking::whereIn('status', ['confirmed', 'checked_in'])
            ->whereDate('check_in', '<=', now())
            ->whereDate('check_out', '>=', now())
            ->pluck('room_id')
            ->filter()
            ->unique();

        $totalRooms = Room::count();
        $occupiedRooms = $occupiedRoomIds->count();

        $occupancyRate = $totalRooms > 0
            ? ($occupiedRooms / $totalRooms) * 100
            : 0;

        /*
        |--------------------------------------------------------------------------
        | TOP ROOMS
        |--------------------------------------------------------------------------
        */

        $topRevenueRooms = $filteredBookings
            ->groupBy('room_id')
            ->map(function ($bookings) {
                $room = optional($bookings->first()->room);

                return [
                    'room_number' => $room->number ?? 'N/A',
                    'revenue' => $bookings->sum('calculated_revenue'),
                    'bookings' => $bookings->count(),
                ];
            })
            ->sortByDesc('revenue')
            ->take(5)
            ->values();

        /*
        |--------------------------------------------------------------------------
        | EXPENSE CATEGORY
        |--------------------------------------------------------------------------
        */

        $expenseByCategory = Expense::select('category', DB::raw('SUM(amount) as total'))
            ->whereDate('expense_date', '>=', $startDate)
            ->whereDate('expense_date', '<=', $endDate)
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | CHART DATA
        |--------------------------------------------------------------------------
        */

        $monthlyChartData = collect(range(1, 12))->map(function ($month) {
            $bookings = Booking::with('room')
                ->whereIn('status', $this->validStatuses)
                ->whereMonth('check_in', $month)
                ->whereYear('check_in', now()->year)
                ->get();

            return [
                'month' => Carbon::create()->month($month)->format('M'),
                'revenue' => $this->calculateRevenue($bookings),
                'expenses' => Expense::whereMonth('expense_date', $month)
                    ->whereYear('expense_date', now()->year)
                    ->sum('amount'),
            ];
        });

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view('finance.index', compact(
            'filter',
            'startDate',
            'endDate',
            'totalRevenue',
            'rangeRevenue',
            'totalExpenses',
            'rangeExpenses',
            'netProfit',
            'rangeProfit',
            'recentExpenses',
            'recentRevenueBookings',
            'revenueGrowth',
            'expenseGrowth',
            'profitGrowth',
            'totalBookings',
            'averageBookingValue',
            'averageStayLength',
            'occupancyRate',
            'topRevenueRooms',
            'expenseByCategory',
            'monthlyChartData'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    private function calculateRevenue($bookings): float
    {
        return (float) $bookings->sum(fn ($booking) => $this->calculateBookingRevenue($booking));
    }

    private function calculateBookingRevenue(Booking $booking): float
    {
        if (!$booking->room) {
            return 0;
        }

        return (float) ($this->calculateNights($booking->check_in, $booking->check_out) * $booking->room->price);
    }

    private function calculateNights($checkInDate, $checkOutDate): int
    {
        return max(1, Carbon::parse($checkInDate)->diffInDays(Carbon::parse($checkOutDate)));
    }

    private function calculateGrowth(float $current, float $previous): float
    {
        if ($previous == 0.0) {
            return $current > 0 ? 100.0 : 0.0;
        }

        return (($current - $previous) / $previous) * 100;
    }

    private function getDateRange(string $filter): array
    {
        switch ($filter) {
            case 'today':
                $start = now()->startOfDay();
                $end = now()->endOfDay();
                $previousStart = now()->copy()->subDay()->startOfDay();
                $previousEnd = now()->copy()->subDay()->endOfDay();
                break;

            case 'this_week':
                $start = now()->startOfWeek();
                $end = now()->endOfWeek();
                $previousStart = now()->copy()->subWeek()->startOfWeek();
                $previousEnd = now()->copy()->subWeek()->endOfWeek();
                break;

            case 'this_year':
                $start = now()->startOfYear();
                $end = now()->endOfYear();
                $previousStart = now()->copy()->subYear()->startOfYear();
                $previousEnd = now()->copy()->subYear()->endOfYear();
                break;

            case 'this_month':
            default:
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
                $previousStart = now()->copy()->subMonth()->startOfMonth();
                $previousEnd = now()->copy()->subMonth()->endOfMonth();
                break;
        }

        return [
            $start->toDateString(),
            $end->toDateString(),
            $previousStart->toDateString(),
            $previousEnd->toDateString(),
        ];
    }
}