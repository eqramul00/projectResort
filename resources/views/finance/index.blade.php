<x-app-layout>
    <style>
        .finance-dashboard {
            padding: 24px;
        }

        .finance-dashboard .grid-1 {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .finance-dashboard .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .finance-dashboard .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .finance-dashboard .grid-4 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .finance-dashboard .card {
            background: #ffffff;
            border-radius: 24px;
            padding: 26px;
            box-shadow: 0 10px 30px rgba(15,23,42,.06);
            border: 1px solid #e5e7eb;
        }

        .finance-dashboard .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 22px;
        }

        .finance-dashboard .page-title-badge {
            display: inline-block;
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 800;
            border-radius: 999px;
            background: #dbeafe;
            color: #0c5a8d;
            letter-spacing: .3px;
            margin-bottom: 12px;
        }

        .finance-dashboard .card h2 {
            margin: 0 0 10px;
            font-size: 24px;
            line-height: 1.2;
            font-weight: 800;
            color: #0f172a;
        }

        .finance-dashboard .muted {
            margin: 0;
            color: #64748b;
            font-size: 15px;
            line-height: 1.6;
        }

        .finance-dashboard .filter-form {
            display: flex;
            align-items: center;
        }

        .finance-dashboard .filter-select {
            padding: 12px 16px;
            border-radius: 14px;
            border: 1px solid #dbe2ea;
            background: #ffffff;
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            cursor: pointer;
        }

        .finance-dashboard .stats-card {
            border-radius: 24px;
            padding: 26px 24px;
            color: #ffffff;
            box-shadow: 0 10px 28px rgba(15,23,42,.08);
        }

        .finance-dashboard .stats-card h4 {
            margin: 0 0 14px;
            font-size: 16px;
            font-weight: 700;
        }

        .finance-dashboard .stats-card .value {
            margin: 0;
            font-size: 34px;
            line-height: 1.15;
            font-weight: 900;
            letter-spacing: -.5px;
            overflow-wrap: anywhere;
        }

        .finance-dashboard .stats-card .trend {
            margin-top: 16px;
            font-size: 14px;
            line-height: 1.5;
        }

        .finance-dashboard .bg-teal {
            background: linear-gradient(135deg, #0f8f8a, #20c5b7);
        }

        .finance-dashboard .bg-orange {
            background: linear-gradient(135deg, #de5a0f, #f79337);
        }

        .finance-dashboard .bg-blue {
            background: linear-gradient(135deg, #2f5fe3, #5c96ef);
        }

        .finance-dashboard .bg-red {
            background: linear-gradient(135deg, #c62828, #ef5350);
        }

        .finance-dashboard .mini-stat {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            padding: 20px;
        }

        .finance-dashboard .mini-stat h5 {
            margin: 0 0 8px;
            font-size: 14px;
            font-weight: 700;
            color: #64748b;
        }

        .finance-dashboard .mini-stat p {
            margin: 0;
            font-size: 28px;
            font-weight: 900;
            color: #0f172a;
            overflow-wrap: anywhere;
        }

        .finance-dashboard .mini-stat small {
            display: block;
            margin-top: 8px;
            color: #64748b;
            line-height: 1.5;
        }

        .finance-dashboard .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 10px;
        }

        .finance-dashboard .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 18px;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 800;
            text-decoration: none;
            transition: all .2s ease;
        }

        .finance-dashboard .btn:hover {
            transform: translateY(-1px);
            opacity: .95;
        }

        .finance-dashboard .btn-primary {
            background: #11998e;
            color: #ffffff;
        }

        .finance-dashboard .btn-secondary {
            background: #e8eefc;
            color: #173f9a;
        }

        .finance-dashboard .btn-warning {
            background: #f7ead7;
            color: #ba6a00;
        }

        .finance-dashboard .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .finance-dashboard table {
            width: 100%;
            border-collapse: collapse;
        }

        .finance-dashboard table thead th {
            text-align: left;
            padding: 14px 12px;
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
            font-weight: 800;
            color: #475569;
            white-space: nowrap;
        }

        .finance-dashboard table tbody td {
            padding: 14px 12px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
            color: #0f172a;
            vertical-align: middle;
        }

        .finance-dashboard table tbody tr:hover {
            background: #fafcff;
        }

        .finance-dashboard .status {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            text-transform: capitalize;
            white-space: nowrap;
        }

        .finance-dashboard .status-confirmed {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .finance-dashboard .status-checked_in {
            background: #dcfce7;
            color: #15803d;
        }

        .finance-dashboard .status-checked_out {
            background: #ede9fe;
            color: #7c3aed;
        }

        .finance-dashboard .review-label {
            color: #92400e;
            font-weight: 700;
        }

        .finance-dashboard .notice {
            padding: 16px 18px;
            border-radius: 16px;
            margin: 20px 0;
            line-height: 1.7;
        }

        .finance-dashboard .notice-info {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
        }

        .finance-dashboard .notice-warning {
            background: #fffbeb;
            border: 1px solid #fcd34d;
            color: #92400e;
        }

        .finance-dashboard .chart-box {
            position: relative;
            width: 100%;
            min-height: 360px;
        }

        .finance-dashboard .chart-box canvas {
            width: 100% !important;
            height: 340px !important;
        }

        .finance-dashboard .summary-stack {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .finance-dashboard :focus-visible {
            outline: 3px solid #2563eb;
            outline-offset: 3px;
        }

        @media (max-width: 1200px) {
            .finance-dashboard .grid-4 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 992px) {
            .finance-dashboard .grid-2,
            .finance-dashboard .grid-3,
            .finance-dashboard .grid-4 {
                grid-template-columns: 1fr;
            }

            .finance-dashboard .card-header {
                flex-direction: column;
            }

            .finance-dashboard .stats-card .value {
                font-size: 28px;
            }
        }

        @media (max-width: 640px) {
            .finance-dashboard {
                padding: 14px;
            }

            .finance-dashboard .card,
            .finance-dashboard .stats-card {
                padding: 20px;
            }

            .finance-dashboard .mini-stat p {
                font-size: 24px;
            }

            .finance-dashboard .actions {
                flex-direction: column;
                align-items: stretch;
            }

            .finance-dashboard .btn {
                width: 100%;
            }
        }
    </style>

    @php
        $pricedBookingCount = max(
            0,
            $totalBookings - $rangeExcludedBookingCount
        );
    @endphp

    <div class="finance-dashboard">
        <div class="card">
            <div class="card-header">
                <div>
                    <span class="page-title-badge">FINANCE OVERVIEW</span>
                    <h2>Booking Values and Expenses</h2>

                    <p class="muted">
                        From {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}
                        to {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                    </p>
                </div>

                <form method="GET"
                      action="{{ route('finance.index') }}"
                      class="filter-form">
                    <select name="range"
                            aria-label="Reporting period"
                            onchange="this.form.submit()"
                            class="filter-select">
                        <option value="today" {{ $filter === 'today' ? 'selected' : '' }}>
                            Today
                        </option>
                        <option value="this_week" {{ $filter === 'this_week' ? 'selected' : '' }}>
                            This Week
                        </option>
                        <option value="this_month" {{ $filter === 'this_month' ? 'selected' : '' }}>
                            This Month
                        </option>
                        <option value="this_year" {{ $filter === 'this_year' ? 'selected' : '' }}>
                            This Year
                        </option>
                    </select>
                </form>
            </div>

            <p class="muted">
                Booking values use the saved agreed nightly rate for confirmed,
                checked-in and checked-out bookings. The whole stay is assigned
                to its arrival date. Expenses use their recorded expense dates.
                These figures do not show cash received or accounting profit.
            </p>

            <div class="actions">
                <a href="{{ route('expenses.create') }}"
                   class="btn btn-primary">
                    + Add Expense
                </a>

                <a href="{{ route('expenses.index') }}"
                   class="btn btn-secondary">
                    View Expenses
                </a>

                <a href="{{ route('bookings.index') }}"
                   class="btn btn-warning">
                    View Bookings
                </a>
            </div>
        </div>

        @if($excludedBookingCount > 0)
            <div class="notice notice-warning" role="status">
                <strong>Some booking values require review.</strong>

                {{ $excludedBookingCount }} qualifying booking(s) across all dates
                have a missing or invalid agreed rate, or invalid stay dates.
                Their values are excluded from monetary totals and room rankings.

                {{ $rangeExcludedBookingCount }} of these booking(s) fall within
                the selected arrival period. Totals may therefore be incomplete.
            </div>
        @else
            <div class="notice notice-info">
                Monetary totals use saved booking rates.
                Later changes to current room prices do not change these values.
            </div>
        @endif

        <div class="grid-3">
            <div class="stats-card bg-teal">
                <h4>Booking Value</h4>
                <p class="value">
                    ৳ {{ number_format($rangeRevenue, 2) }}
                </p>
                <div class="trend">
                    Whole-stay values for arrivals in the selected period.
                </div>
            </div>

            <div class="stats-card bg-orange">
                <h4>Recorded Expenses</h4>
                <p class="value">
                    ৳ {{ number_format($rangeExpenses, 2) }}
                </p>
                <div class="trend">
                    Expenses dated within the selected period.
                </div>
            </div>

            <div class="stats-card {{ $rangeProfit >= 0 ? 'bg-blue' : 'bg-red' }}">
                <h4>Booking Value Less Expenses</h4>
                <p class="value">
                    ৳ {{ number_format($rangeProfit, 2) }}
                </p>
                <div class="trend">
                    A planning comparison, not accounting profit.
                </div>
            </div>
        </div>

        <div class="grid-4">
            <div class="mini-stat">
                <h5>Qualifying Bookings</h5>
                <p>{{ $totalBookings }}</p>
                <small>
                    Confirmed, checked-in and checked-out arrivals
                    in the selected period.
                </small>
            </div>

            <div class="mini-stat">
                <h5>Average Booking Value</h5>

                @if($pricedBookingCount > 0)
                    <p>৳ {{ number_format($averageBookingValue, 2) }}</p>
                @else
                    <p>N/A</p>
                @endif

                <small>
                    Based on {{ $pricedBookingCount }} booking(s)
                    with usable saved rates and stay dates.
                </small>
            </div>

            <div class="mini-stat">
                <h5>Average Stay Length</h5>

                @if($averageStayLength > 0)
                    <p>{{ number_format($averageStayLength, 1) }} nights</p>
                @else
                    <p>N/A</p>
                @endif

                <small>
                    Selected-period bookings with valid stay dates.
                </small>
            </div>

            <div class="mini-stat">
                <h5>Scheduled Occupancy Today</h5>
                <p>{{ number_format($occupancyRate, 2) }}%</p>
                <small>
                    Confirmed and checked-in stays scheduled for today,
                    divided by all rooms. Independent of the period filter.
                </small>
            </div>
        </div>

        <div class="grid-2">
            <div class="card">
                <div class="card-header">
                    <div>
                        <span class="page-title-badge">CHART</span>
                        <h2>Booking Values vs Expenses</h2>
                        <p class="muted">
                            Monthly totals for {{ now()->year }}.
                            Booking values are grouped by arrival month;
                            expenses by expense month.
                        </p>
                    </div>
                </div>

                <div class="chart-box">
                    <canvas
                        id="financeChart"
                        aria-label="Monthly booking values and recorded expenses"
                        role="img"
                        data-labels="{{ $monthlyChartData->pluck('month')->values()->toJson() }}"
                        data-revenue="{{ $monthlyChartData->pluck('revenue')->values()->toJson() }}"
                        data-expenses="{{ $monthlyChartData->pluck('expenses')->values()->toJson() }}"
                    >
                        Monthly figures are listed in the table below.
                    </canvas>
                </div>

                <p id="chartMessage" class="muted" hidden>
                    The chart could not load. Use the monthly table below.
                </p>

                <details style="margin-top:16px;">
                    <summary class="muted" style="cursor:pointer;">
                        View monthly figures
                    </summary>

                    <div class="table-responsive" style="margin-top:12px;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Booking Value</th>
                                    <th>Expenses</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthlyChartData as $month)
                                    <tr>
                                        <td>{{ $month['month'] }}</td>
                                        <td>
                                            ৳ {{ number_format($month['revenue'], 2) }}
                                        </td>
                                        <td>
                                            ৳ {{ number_format($month['expenses'], 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </details>
            </div>

            <div class="card">
                <div class="card-header">
                    <div>
                        <span class="page-title-badge">EXPENSE BREAKDOWN</span>
                        <h2>Expenses by Category</h2>
                        <p class="muted">
                            Recorded expenses in the selected period.
                        </p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($expenseByCategory as $item)
                                <tr>
                                    <td>{{ $item->category ?? 'Uncategorized' }}</td>
                                    <td>৳ {{ number_format($item->total, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="muted">
                                        No category data found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="grid-2">
            <div class="card">
                <div class="card-header">
                    <div>
                        <span class="page-title-badge">TOP ROOMS</span>
                        <h2>Highest Booking Value Rooms</h2>
                        <p class="muted">
                            Selected arrival period. Only bookings with
                            usable saved rates and stay dates are included.
                        </p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Room</th>
                                <th>Priced Bookings</th>
                                <th>Booking Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topRevenueRooms as $room)
                                <tr>
                                    <td>{{ $room['room_number'] }}</td>
                                    <td>{{ $room['bookings'] }}</td>
                                    <td>
                                        ৳ {{ number_format($room['revenue'], 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="muted">
                                        No priced booking data found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div>
                        <span class="page-title-badge">RECENT BOOKINGS</span>
                        <h2>Recent Qualifying Bookings</h2>
                        <p class="muted">
                            Latest created confirmed, checked-in or
                            checked-out bookings across all dates.
                        </p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Guest</th>
                                <th>Room</th>
                                <th>Nights</th>
                                <th>Booking Value</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentRevenueBookings as $booking)
                                <tr>
                                    <td>
                                        <a href="{{ route('bookings.show', $booking) }}">
                                            #{{ $booking->id }}
                                        </a>
                                    </td>

                                    <td>
                                        {{ $booking->guest->full_name ?? 'N/A' }}
                                    </td>

                                    <td>
                                        {{ $booking->room->number ?? 'N/A' }}
                                    </td>

                                    <td>
                                        @if($booking->nights > 0)
                                            {{ $booking->nights }}
                                        @else
                                            Requires review
                                        @endif
                                    </td>

                                    <td>
                                        @if($booking->calculated_revenue !== null)
                                            ৳ {{ number_format($booking->calculated_revenue, 2) }}
                                        @else
                                            <span class="review-label">
                                                Requires review
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="status status-{{ $booking->status }}">
                                            {{ ucwords(str_replace('_', ' ', $booking->status)) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="muted">
                                        No qualifying bookings found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="grid-2">
            <div class="card">
                <div class="card-header">
                    <div>
                        <span class="page-title-badge">RECENT EXPENSES</span>
                        <h2>Latest Expenses</h2>
                        <p class="muted">
                            Latest expense dates across all periods.
                        </p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentExpenses as $expense)
                                <tr>
                                    <td>{{ $expense->title }}</td>
                                    <td>
                                        ৳ {{ number_format($expense->amount, 2) }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="muted">
                                        No expenses found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div>
                        <span class="page-title-badge">ALL-DATE TOTALS</span>
                        <h2>Overall Overview</h2>
                        <p class="muted">
                            Includes past and future qualifying bookings.
                            Independent of the selected period.
                        </p>
                    </div>
                </div>

                <div class="summary-stack">
                    <div class="mini-stat">
                        <h5>Total Booking Value</h5>
                        <p>৳ {{ number_format($totalRevenue, 2) }}</p>
                        <small>
                            Excludes bookings with unusable rates or dates.
                        </small>
                    </div>

                    <div class="mini-stat">
                        <h5>Total Recorded Expenses</h5>
                        <p>৳ {{ number_format($totalExpenses, 2) }}</p>
                    </div>

                    <div class="mini-stat">
                        <h5>Booking Value Less Expenses</h5>
                        <p>৳ {{ number_format($netProfit, 2) }}</p>
                        <small>
                            This is not a cash balance or accounting profit.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        (() => {
            const canvas = document.getElementById('financeChart');
            const message = document.getElementById('chartMessage');

            if (!canvas) {
                return;
            }

            if (typeof Chart === 'undefined') {
                if (message) {
                    message.hidden = false;
                }

                return;
            }

            try {
                const labels = JSON.parse(canvas.dataset.labels || '[]');
                const bookingValues = JSON.parse(canvas.dataset.revenue || '[]');
                const expenses = JSON.parse(canvas.dataset.expenses || '[]');

                new Chart(canvas, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Booking Value',
                                data: bookingValues,
                                backgroundColor: 'rgba(20,184,166,.75)',
                                borderRadius: 8
                            },
                            {
                                label: 'Recorded Expenses',
                                data: expenses,
                                backgroundColor: 'rgba(249,115,22,.75)',
                                borderRadius: 8
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function (value) {
                                        return '৳ ' + value;
                                    }
                                }
                            }
                        }
                    }
                });
            } catch (error) {
                if (message) {
                    message.hidden = false;
                }

                console.error('Unable to display finance chart.', error);
            }
        })();
    </script>
</x-app-layout>