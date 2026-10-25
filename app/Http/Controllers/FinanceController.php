<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Expense;
use App\Models\Room;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use LogicException;
use Throwable;

class FinanceController extends Controller
{
    private array $validStatuses = [
        'confirmed',
        'checked_in',
        'checked_out',
    ];

    public function index(Request $request): View
    {
        $validated = $request->validate([
            'range' => [
                'sometimes',
                'string',
                'in:today,this_week,this_month,this_year',
            ],
        ]);

        $filter = $validated['range'] ?? 'this_month';

        [
            $startDate,
            $endDate,
            $previousStartDate,
            $previousEndDate,
        ] = $this->getDateRange($filter);

        $allBookings = $this->fetchBookings();

        $filteredBookings = $this->fetchBookings(
            $startDate,
            $endDate
        );

        $previousBookings = $this->fetchBookings(
            $previousStartDate,
            $previousEndDate
        );

        $excludedBookingCount = $this->excludedCount($allBookings);

        $rangeExcludedBookingCount = $this->excludedCount(
            $filteredBookings
        );

        $totalRevenue = $this->calculateRevenue($allBookings);
        $rangeRevenue = $this->calculateRevenue($filteredBookings);
        $previousRevenue = $this->calculateRevenue($previousBookings);

        $totalExpenses = (float) Expense::sum('amount');

        $rangeExpenses = $this->expenseTotal(
            $startDate,
            $endDate
        );

        $previousExpenses = $this->expenseTotal(
            $previousStartDate,
            $previousEndDate
        );

        $netProfit = round($totalRevenue - $totalExpenses, 2);
        $rangeProfit = round($rangeRevenue - $rangeExpenses, 2);

        $previousProfit = round(
            $previousRevenue - $previousExpenses,
            2
        );

        $revenueGrowth = $this->calculateGrowth(
            $rangeRevenue,
            $previousRevenue
        );

        $expenseGrowth = $this->calculateGrowth(
            $rangeExpenses,
            $previousExpenses
        );

        $profitGrowth = $this->calculateGrowth(
            $rangeProfit,
            $previousProfit
        );

        $recentExpenses = Expense::latest('expense_date')
            ->take(8)
            ->get();

        $recentRevenueBookings = $this->fetchBookings(
            null,
            null,
            true
        );

        $totalBookings = $filteredBookings->count();

        $pricedBookings = $this->pricedBookings($filteredBookings);

        $averageBookingValue = $pricedBookings->isNotEmpty()
            ? round($rangeRevenue / $pricedBookings->count(), 2)
            : 0.0;

        $averageStayLength = $this->averageStayLength(
            $filteredBookings
        );

        $occupancyRate = $this->occupancyRate();

        $topRevenueRooms = $this->topRooms($pricedBookings);

        $expenseByCategory = $this->expenseCategories(
            $startDate,
            $endDate
        );

        $monthlyChartData = $this->monthlyChart();

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
            'monthlyChartData',
            'excludedBookingCount',
            'rangeExcludedBookingCount'
        ));
    }

    /** @return Collection<int, Booking> */
    private function fetchBookings(
        ?string $start = null,
        ?string $end = null,
        bool $recent = false
    ): Collection {
        /** @var Builder<Booking> $query */
        $query = Booking::query();

        $query->with(['room', 'guest'])
            ->whereIn('status', $this->validStatuses);

        if ($start !== null) {
            $query->whereDate('check_in', '>=', $start);
        }

        if ($end !== null) {
            $query->whereDate('check_in', '<=', $end);
        }

        if ($recent) {
            $query->latest('created_at')->take(8);
        }

        /** @var Collection<int, Booking> $bookings */
        $bookings = new Collection();

        foreach ($query->get() as $record) {
            if (!$record instanceof Booking) {
                throw new LogicException(
                    'The finance query must return Booking models.'
                );
            }

            $bookings->push($this->prepareBooking($record));
        }

        return $bookings;
    }

    private function prepareBooking(Booking $booking): Booking
    {
        $booking->setAttribute(
            'nights',
            $this->calculateNights(
                $booking->check_in,
                $booking->check_out
            )
        );

        $booking->setAttribute(
            'calculated_revenue',
            $this->hasUsableRateAndDates($booking)
                ? $this->calculateBookingRevenue($booking)
                : null
        );

        return $booking;
    }

    private function hasUsableRateAndDates(Booking $booking): bool
    {
        return $booking->agreed_rate !== null
            && is_numeric($booking->agreed_rate)
            && (float) $booking->agreed_rate >= 0
            && $this->calculateNights(
                $booking->check_in,
                $booking->check_out
            ) > 0;
    }

    /** @param Collection<int, Booking> $bookings */
    private function excludedCount(Collection $bookings): int
    {
        $count = 0;

        foreach ($bookings as $booking) {
            if (!$this->hasUsableRateAndDates($booking)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * @param Collection<int, Booking> $bookings
     * @return Collection<int, Booking>
     */
    private function pricedBookings(Collection $bookings): Collection
    {
        return $bookings->filter(
            fn (Booking $booking): bool =>
                $this->hasUsableRateAndDates($booking)
        );
    }

    /** @param Collection<int, Booking> $bookings */
    private function calculateRevenue(Collection $bookings): float
    {
        $totalMinor = 0;

        foreach ($bookings as $booking) {
            if (!$this->hasUsableRateAndDates($booking)) {
                continue;
            }

            $rateMinor = (int) round(
                (float) $booking->agreed_rate * 100
            );

            $nights = $this->calculateNights(
                $booking->check_in,
                $booking->check_out
            );

            $totalMinor += $rateMinor * $nights;
        }

        return $totalMinor / 100;
    }

    private function calculateBookingRevenue(Booking $booking): float
    {
        if (!$this->hasUsableRateAndDates($booking)) {
            return 0.0;
        }

        $rateMinor = (int) round(
            (float) $booking->agreed_rate * 100
        );

        $nights = $this->calculateNights(
            $booking->check_in,
            $booking->check_out
        );

        return ($rateMinor * $nights) / 100;
    }

    private function calculateNights(
        string|DateTimeInterface|null $checkInDate,
        string|DateTimeInterface|null $checkOutDate
    ): int {
        if (!$checkInDate || !$checkOutDate) {
            return 0;
        }

        try {
            $checkIn = Carbon::parse($checkInDate)->startOfDay();
            $checkOut = Carbon::parse($checkOutDate)->startOfDay();

            if (!$checkOut->greaterThan($checkIn)) {
                return 0;
            }

            return (int) $checkIn->diffInDays($checkOut);
        } catch (Throwable $exception) {
            return 0;
        }
    }

    /** @param Collection<int, Booking> $bookings */
    private function averageStayLength(Collection $bookings): float
    {
        $nights = 0;
        $count = 0;

        foreach ($bookings as $booking) {
            $stay = $this->calculateNights(
                $booking->check_in,
                $booking->check_out
            );

            if ($stay > 0) {
                $nights += $stay;
                $count++;
            }
        }

        return $count > 0 ? $nights / $count : 0.0;
    }

    private function expenseTotal(string $start, string $end): float
    {
        return (float) Expense::whereDate(
            'expense_date',
            '>=',
            $start
        )
            ->whereDate('expense_date', '<=', $end)
            ->sum('amount');
    }

    private function occupancyRate(): float
    {
        $today = Carbon::now()->toDateString();

        $occupied = Booking::whereIn(
            'status',
            ['confirmed', 'checked_in']
        )
            ->whereDate('check_in', '<=', $today)
            ->whereDate('check_out', '>', $today)
            ->pluck('room_id')
            ->filter()
            ->unique()
            ->count();

        $total = Room::count();

        return $total > 0 ? ($occupied / $total) * 100 : 0.0;
    }

    /**
     * @param Collection<int, Booking> $bookings
     * @return Collection<int, array{room_number: mixed, revenue: float, bookings: int}>
     */
    private function topRooms(Collection $bookings): Collection
    {
        $rows = [];

        foreach ($bookings->groupBy('room_id') as $group) {
            $first = $group->first();

            $rows[] = [
                'room_number' => $first?->room?->number ?? 'N/A',
                'revenue' => $this->calculateRevenue($group),
                'bookings' => $group->count(),
            ];
        }

        return collect($rows)
            ->sortByDesc('revenue')
            ->take(5)
            ->values();
    }

    private function expenseCategories(
        string $start,
        string $end
    ): Collection {
        return Expense::select(
            'category',
            DB::raw('SUM(amount) as total')
        )
            ->whereDate('expense_date', '>=', $start)
            ->whereDate('expense_date', '<=', $end)
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();
    }

    private function monthlyChart(): Collection
    {
        $year = Carbon::now()->year;
        $rows = [];

        for ($month = 1; $month <= 12; $month++) {
            $date = Carbon::create($year, $month, 1);

            $start = $date->copy()
                ->startOfMonth()
                ->toDateString();

            $end = $date->copy()
                ->endOfMonth()
                ->toDateString();

            $rows[] = [
                'month' => $date->format('M'),
                'revenue' => $this->calculateRevenue(
                    $this->fetchBookings($start, $end)
                ),
                'expenses' => $this->expenseTotal($start, $end),
            ];
        }

        return collect($rows);
    }

    private function calculateGrowth(
        float $current,
        float $previous
    ): float {
        if ($previous == 0.0) {
            return $current > 0 ? 100.0 : 0.0;
        }

        return (($current - $previous) / $previous) * 100;
    }

    /** @return array{string, string, string, string} */
    private function getDateRange(string $filter): array
    {
        $now = Carbon::now();

        switch ($filter) {
            case 'today':
                $start = $now->copy()->startOfDay();
                $end = $now->copy()->endOfDay();

                $previousStart = $start->copy()->subDay();
                $previousEnd = $previousStart->copy()->endOfDay();
                break;

            case 'this_week':
                $start = $now->copy()->startOfWeek();
                $end = $now->copy()->endOfWeek();

                $previousStart = $start->copy()->subWeek();
                $previousEnd = $previousStart->copy()->endOfWeek();
                break;

            case 'this_year':
                $start = $now->copy()->startOfYear();
                $end = $now->copy()->endOfYear();

                $previousStart = $start->copy()->subYear();
                $previousEnd = $previousStart->copy()->endOfYear();
                break;

            default:
                $start = $now->copy()->startOfMonth();
                $end = $now->copy()->endOfMonth();

                $previousStart = $start->copy()->subMonth();
                $previousEnd = $previousStart->copy()->endOfMonth();
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