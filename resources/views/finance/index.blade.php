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
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
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
            letter-spacing: 0.3px;
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
        }

        .finance-dashboard .filter-form {
            display: flex;
            align-items: center;
        }

        .finance-dashboard .filter-select {
            padding: 12px 16px;
            border-radius: 14px;
            border: 1px solid #dbe2ea;
            background: #fff;
            outline: none;
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            cursor: pointer;
        }

        .finance-dashboard .stats-card {
            border-radius: 24px;
            padding: 26px 24px;
            color: #fff;
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.08);
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
            letter-spacing: -0.5px;
        }

        .finance-dashboard .stats-card .trend {
            margin-top: 16px;
            font-size: 14px;
            font-weight: 700;
            opacity: 0.98;
        }

        .finance-dashboard .trend.up {
            color: #dcfce7;
        }

        .finance-dashboard .trend.down {
            color: #fee2e2;
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

        .finance-dashboard .bg-purple {
            background: linear-gradient(135deg, #7a3fe3, #a385f4);
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
            transition: all 0.2s ease;
        }

        .finance-dashboard .btn:hover {
            transform: translateY(-1px);
            opacity: 0.95;
        }

        .finance-dashboard .btn-primary {
            background: #11998e;
            color: #fff;
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

    <div class="finance-dashboard">
        <div class="card">
            <div class="card-header">
                <div>
                    <span class="page-title-badge">ADVANCED FINANCE</span>
                    <h2>Financial Analytics Dashboard</h2>
                    <p class="muted">
                        From {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}
                        to {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                    </p>
                </div>

                <form method="GET" action="{{ route('finance.index') }}" class="filter-form">
                    <select name="range" onchange="this.form.submit()" class="filter-select">
                        <option value="today" {{ $filter == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="this_week" {{ $filter == 'this_week' ? 'selected' : '' }}>This Week</option>
                        <option value="this_month" {{ $filter == 'this_month' ? 'selected' : '' }}>This Month</option>
                        <option value="this_year" {{ $filter == 'this_year' ? 'selected' : '' }}>This Year</option>
                    </select>
                </form>
            </div>

            <div class="actions">
                <a href="{{ route('expenses.create') }}" class="btn btn-primary">+ Add Expense</a>
                <a href="{{ route('expenses.index') }}" class="btn btn-secondary">View Expenses</a>
                <a href="{{ route('bookings.index') }}" class="btn btn-warning">View Bookings</a>
            </div>
        </div>

        <div class="grid-3">
            <div class="stats-card bg-teal">
                <h4>Revenue</h4>
                <p class="value">৳ {{ number_format($rangeRevenue, 2) }}</p>
                <div class="trend {{ $revenueGrowth >= 0 ? 'up' : 'down' }}">
                    {{ $revenueGrowth >= 0 ? '↑' : '↓' }}
                    {{ number_format(abs($revenueGrowth), 2) }}% vs previous period
                </div>
            </div>

            <div class="stats-card bg-orange">
                <h4>Expenses</h4>
                <p class="value">৳ {{ number_format($rangeExpenses, 2) }}</p>
                <div class="trend {{ $expenseGrowth <= 0 ? 'up' : 'down' }}">
                    {{ $expenseGrowth >= 0 ? '↑' : '↓' }}
                    {{ number_format(abs($expenseGrowth), 2) }}% vs previous period
                </div>
            </div>

            <div class="stats-card {{ $rangeProfit >= 0 ? 'bg-blue' : 'bg-red' }}">
                <h4>Profit</h4>
                <p class="value">৳ {{ number_format($rangeProfit, 2) }}</p>
                <div class="trend {{ $profitGrowth >= 0 ? 'up' : 'down' }}">
                    {{ $profitGrowth >= 0 ? '↑' : '↓' }}
                    {{ number_format(abs($profitGrowth), 2) }}% vs previous period
                </div>
            </div>
        </div>

        <div class="grid-4">
            <div class="mini-stat">
                <h5>Total Bookings</h5>
                <p>{{ $totalBookings }}</p>
            </div>

            <div class="mini-stat">
                <h5>Avg Booking Value</h5>
                <p>৳ {{ number_format($averageBookingValue, 2) }}</p>
            </div>

            <div class="mini-stat">
                <h5>Avg Stay Length</h5>
                <p>{{ number_format($averageStayLength, 1) }}N</p>
            </div>

            <div class="mini-stat">
                <h5>Occupancy Rate</h5>
                <p>{{ number_format($occupancyRate, 2) }}%</p>
            </div>
        </div>

        <div class="grid-2">
            <div class="card">
                <div class="card-header">
                    <div>
                        <span class="page-title-badge">CHART</span>
                        <h2>Revenue vs Expenses</h2>
                        <p class="muted">Monthly financial performance for the current year.</p>
                    </div>
                </div>

                <div class="chart-box">
                    <canvas
                        id="financeChart"
                        data-labels='@json($monthlyChartData->pluck("month")->values())'
                        data-revenue='@json($monthlyChartData->pluck("revenue")->values())'
                        data-expenses='@json($monthlyChartData->pluck("expenses")->values())'
                    ></canvas>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div>
                        <span class="page-title-badge">EXPENSE BREAKDOWN</span>
                        <h2>Expenses by Category</h2>
                        <p class="muted">See which categories consume the most budget.</p>
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
                                    <td colspan="2" class="muted">No category data found.</td>
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
                        <h2>Highest Revenue Rooms</h2>
                        <p class="muted">Best performing rooms in the selected period.</p>
                    </div>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Room</th>
                                <th>Bookings</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topRevenueRooms as $room)
                                <tr>
                                    <td>{{ $room['room_number'] }}</td>
                                    <td>{{ $room['bookings'] }}</td>
                                    <td>৳ {{ number_format($room['revenue'], 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="muted">No room revenue data found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div>
                        <span class="page-title-badge">RECENT REVENUE</span>
                        <h2>Recent Revenue Bookings</h2>
                        <p class="muted">Latest bookings contributing to revenue.</p>
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
                                <th>Revenue</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentRevenueBookings as $booking)
                                <tr>
                                    <td>#{{ $booking->id }}</td>
                                    <td>{{ $booking->guest->full_name ?? 'N/A' }}</td>
                                    <td>{{ $booking->room->number ?? 'N/A' }}</td>
                                    <td>{{ $booking->nights }}</td>
                                    <td>৳ {{ number_format($booking->calculated_revenue ?? 0, 2) }}</td>
                                    <td>
                                        <span class="status status-{{ $booking->status }}">
                                            {{ ucwords(str_replace('_', ' ', $booking->status)) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="muted">No revenue bookings found.</td>
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
                        <p class="muted">Most recently recorded resort expenses.</p>
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
                                    <td>৳ {{ number_format($expense->amount, 2) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="muted">No expenses found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <div>
                        <span class="page-title-badge">LIFETIME TOTALS</span>
                        <h2>Overall Finance</h2>
                        <p class="muted">All-time financial summary.</p>
                    </div>
                </div>

                <div class="summary-stack">
                    <div class="mini-stat">
                        <h5>Total Revenue</h5>
                        <p>৳ {{ number_format($totalRevenue, 2) }}</p>
                    </div>

                    <div class="mini-stat">
                        <h5>Total Expenses</h5>
                        <p>৳ {{ number_format($totalExpenses, 2) }}</p>
                    </div>

                    <div class="mini-stat">
                        <h5>Net Profit</h5>
                        <p>৳ {{ number_format($netProfit, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const financeChart = document.getElementById('financeChart');

        if (financeChart) {
            const chartLabels = JSON.parse(financeChart.dataset.labels || '[]');
            const chartRevenue = JSON.parse(financeChart.dataset.revenue || '[]');
            const chartExpenses = JSON.parse(financeChart.dataset.expenses || '[]');

            new Chart(financeChart, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [
                        {
                            label: 'Revenue',
                            data: chartRevenue,
                            backgroundColor: 'rgba(20, 184, 166, 0.75)',
                            borderRadius: 8
                        },
                        {
                            label: 'Expenses',
                            data: chartExpenses,
                            backgroundColor: 'rgba(249, 115, 22, 0.75)',
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
        }
    </script>
</x-app-layout>