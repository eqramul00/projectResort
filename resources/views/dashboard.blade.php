<x-app-layout>
    <style>
        .dashboard-link {
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .premium-card {
            position: relative;
            overflow: hidden;
            cursor: pointer;
            transition: transform .25s ease, box-shadow .25s ease, filter .25s ease;
        }

        .premium-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 24px 48px rgba(15, 23, 42, .20);
            filter: saturate(1.06);
        }

        .premium-card::before {
            content: "";
            position: absolute;
            top: -40px;
            right: -40px;
            width: 130px;
            height: 130px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .12);
        }

        .premium-card::after {
            content: "";
            position: absolute;
            bottom: -26px;
            left: -26px;
            width: 90px;
            height: 90px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .08);
        }

        .premium-top {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: start;
            justify-content: space-between;
            gap: 14px;
        }

        .premium-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, .08);
            font-size: 13px;
            font-weight: 700;
            opacity: .9;
        }

        .premium-arrow {
            position: relative;
            z-index: 2;
            font-size: 16px;
            font-weight: 900;
            opacity: .85;
            transition: transform .2s ease;
        }

        .premium-card:hover .premium-arrow {
            transform: translateX(4px);
        }

        .premium-content {
            position: relative;
            z-index: 2;
            margin-top: 18px;
        }

        .premium-kicker {
            margin-top: 10px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .12);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .3px;
        }

        .premium-subgrid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .mini-summary {
            background: linear-gradient(180deg, #ffffff, #f8fbff);
            border: 1px solid #e6edf5;
            border-radius: 20px;
            padding: 18px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, .06);
        }

        .mini-summary h4 {
            margin: 0 0 8px;
            color: #64748b;
            font-size: 13px;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        .mini-summary p {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
        }

        .section-shell {
            background: linear-gradient(180deg, #ffffff, #fbfdff);
            border: 1px solid #e7eef6;
            border-radius: 24px;
            padding: 22px;
            box-shadow: 0 16px 36px rgba(15, 23, 42, .06);
        }

        .quick-actions-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
        }

        .action-tile {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 18px;
            border-radius: 18px;
            font-weight: 800;
            border: 1px solid transparent;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .action-tile:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 24px rgba(15, 23, 42, .12);
        }

        .action-primary {
            background: linear-gradient(135deg, #0f766e, #14b8a6);
            color: #ffffff;
        }

        .action-blue {
            background: linear-gradient(135deg, #1d4ed8, #60a5fa);
            color: #ffffff;
        }

        .action-gold {
            background: linear-gradient(135deg, #c9971a, #f0cf76);
            color: #231800;
        }

        .action-dark {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: #ffffff;
        }

        .soft-table-wrap {
            background: linear-gradient(180deg, #ffffff, #fbfdff);
            border: 1px solid #e7eef6;
            border-radius: 24px;
            padding: 22px;
            box-shadow: 0 16px 36px rgba(15, 23, 42, .06);
        }

        .soft-table-wrap table th {
            background: #f8fbff;
        }

        .headline-note {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: #eef6ff;
            color: #1d4ed8;
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .occupancy-forecast {
            min-width: 0;
            padding: 26px;
            border: 1px solid #dce4d6;
            border-radius: 22px;
            background: #fffefa;
            box-shadow: 0 12px 30px rgba(25, 61, 50, .05);
        }

        .of-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 22px;
            margin-bottom: 22px;
        }

        .of-eyebrow {
            display: block;
            margin-bottom: 10px;
            color: #657b51;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.6px;
        }

        .of-header h2 {
            margin: 0 0 10px;
            color: #193d32;
            font: 400 30px/1.2 Georgia, serif;
            letter-spacing: -.6px;
        }

        .of-header p {
            max-width: 590px;
            margin: 0;
            color: #68776c;
            font-size: 12px;
            line-height: 1.8;
        }

        .of-badge {
            display: inline-block;
            flex-shrink: 0;
            padding: 8px 12px;
            border: 1px solid #e4cf9d;
            border-radius: 20px;
            background: #fff4d8;
            color: #735217;
            font-size: 10px;
            font-weight: 700;
        }

        .of-notice {
            padding: 16px 18px;
            border: 1px solid #d7e1cb;
            border-radius: 12px;
            background: #f0f3e9;
            color: #3d5734;
        }

        .of-notice strong {
            font-size: 12px;
        }

        .of-notice p {
            margin: 6px 0 0;
            font-size: 11px;
            line-height: 1.85;
        }

        .of-dated {
            margin: 14px 0 0;
            padding: 12px 16px;
            border-left: 3px solid #c49c50;
            background: #fff8e9;
            color: #79591f;
            font-size: 11px;
            line-height: 1.8;
        }

        .of-metrics {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            margin: 22px 0;
        }

        .of-metrics > div {
            min-width: 0;
            padding: 17px 18px;
            border: 1px solid #e0e6da;
            border-radius: 12px;
            background: #f8f9f4;
        }

        .of-metrics > div > span {
            display: block;
            margin-bottom: 9px;
            color: #68776c;
            font-size: 11px;
        }

        .of-metrics strong {
            display: block;
            color: #193d32;
            font-size: 25px;
            font-weight: 600;
        }

        .of-metrics small {
            color: #68776c;
            font-size: 11px;
            font-weight: 400;
        }

        .of-period {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px 24px;
            margin-bottom: 17px;
        }

        .of-period strong {
            color: #193d32;
            font-size: 12px;
        }

        .of-period > span {
            color: #68776c;
            font-size: 10px;
        }

        .of-table {
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
            border: 1px solid #e1e6db;
            border-radius: 12px;
        }

        .of-table table {
            min-width: 620px;
            width: 100%;
            border-collapse: collapse;
        }

        .of-table thead th {
            padding: 14px 16px;
            background: #eef2e7;
            color: #526548;
            font-size: 10px;
            text-align: left;
            white-space: nowrap;
        }

        .of-table tbody th,
        .of-table td {
            padding: 15px 16px;
            border-bottom: 1px solid #e8ece3;
            color: #304735;
            font-size: 12px;
            text-align: left;
        }

        .of-table tbody th {
            font-weight: 600;
            white-space: nowrap;
        }

        .of-table tbody tr:last-child > * {
            border-bottom: 0;
        }

        .of-table tbody tr:hover {
            background: #f6f8f1;
        }

        .of-muted {
            color: #7a8872;
            font-size: 10px;
        }

        .of-percent {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .of-percent > span:first-child {
            min-width: 50px;
        }

        .of-meter {
            display: block;
            width: 100px;
            height: 8px;
            flex-shrink: 0;
            border: 0;
            border-radius: 10px;
            background: #e5eadc;
            appearance: none;
        }

        .of-meter::-webkit-meter-bar {
            height: 8px;
            border: 0;
            border-radius: 10px;
            background: #e5eadc;
            box-shadow: none;
        }

        .of-meter::-webkit-meter-optimum-value {
            border-radius: 10px;
            background: linear-gradient(90deg, #2c5b43, #91a66d);
        }

        .of-meter::-moz-meter-bar {
            border-radius: 10px;
            background: linear-gradient(90deg, #2c5b43, #91a66d);
        }

        .of-footnote {
            margin: 15px 0 0;
            color: #75816f;
            font-size: 10px;
            line-height: 1.9;
        }

        .of-empty {
            padding: 22px;
            border: 1px dashed #c9d4bd;
            border-radius: 12px;
            background: #f6f8f0;
            color: #4e6542;
        }

        .of-empty p {
            margin: 8px 0 0;
            font-size: 12px;
            line-height: 1.8;
        }

        .of-sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }

        @media (max-width: 1024px) {
            .premium-subgrid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 900px) {
            .of-header {
                flex-direction: column;
                gap: 14px;
            }
        }

        @media (max-width: 640px) {
            .occupancy-forecast {
                padding: 20px 16px;
                border-radius: 16px;
            }

            .of-header h2 {
                font-size: 27px;
            }

            .of-metrics {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .of-metrics > div {
                padding: 14px 16px;
            }

            .of-metrics strong {
                font-size: 23px;
            }
        }
    </style>

    <div class="headline-note">
        Live Resort Control Overview
    </div>

    <div class="grid-3">
        <a href="{{ route('rooms.index') }}" class="dashboard-link">
            <div class="stats-card bg-teal premium-card">
                <div class="premium-top">
                    <div class="premium-icon">R</div>
                    <div class="premium-arrow">→</div>
                </div>
                <div class="premium-content">
                    <h4>Total Rooms</h4>
                    <p class="value">{{ $totalRooms }}</p>
                    <div class="note">Open the full room inventory</div>
                    <div class="premium-kicker">All room categories</div>
                </div>
            </div>
        </a>

        <a href="{{ route('guests.index') }}" class="dashboard-link">
            <div class="stats-card bg-blue premium-card">
                <div class="premium-top">
                    <div class="premium-icon">G</div>
                    <div class="premium-arrow">→</div>
                </div>
                <div class="premium-content">
                    <h4>Total Guests</h4>
                    <p class="value">{{ $totalGuests }}</p>
                    <div class="note">Browse all guest profiles</div>
                    <div class="premium-kicker">Guest records</div>
                </div>
            </div>
        </a>

        <a href="{{ route('bookings.index') }}" class="dashboard-link">
            <div class="stats-card bg-purple premium-card">
                <div class="premium-top">
                    <div class="premium-icon">B</div>
                    <div class="premium-arrow">→</div>
                </div>
                <div class="premium-content">
                    <h4>Total Bookings</h4>
                    <p class="value">{{ $totalBookings }}</p>
                    <div class="note">See all reservations</div>
                    <div class="premium-kicker">All statuses</div>
                </div>
            </div>
        </a>
    </div>

    <div class="grid-3">
        <a href="{{ route('rooms.index', ['status' => 'available']) }}"
           class="dashboard-link">
            <div class="stats-card bg-teal premium-card">
                <div class="premium-top">
                    <div class="premium-icon">A</div>
                    <div class="premium-arrow">→</div>
                </div>
                <div class="premium-content">
                    <h4>Available Rooms</h4>
                    <p class="value">{{ $availableRooms }}</p>
                    <div class="note">Ready for new reservations</div>
                    <div class="premium-kicker">Bookable inventory</div>
                </div>
            </div>
        </a>

        <a href="{{ route('rooms.index', ['status' => 'occupied']) }}"
           class="dashboard-link">
            <div class="stats-card bg-blue premium-card">
                <div class="premium-top">
                    <div class="premium-icon">O</div>
                    <div class="premium-arrow">→</div>
                </div>
                <div class="premium-content">
                    <h4>Occupied Rooms</h4>
                    <p class="value">{{ $occupiedRooms }}</p>
                    <div class="note">Currently in guest use</div>
                    <div class="premium-kicker">Active stays</div>
                </div>
            </div>
        </a>

        <a href="{{ route('rooms.index', ['status' => 'maintenance']) }}"
           class="dashboard-link">
            <div class="stats-card bg-orange premium-card">
                <div class="premium-top">
                    <div class="premium-icon">M</div>
                    <div class="premium-arrow">→</div>
                </div>
                <div class="premium-content">
                    <h4>Maintenance Rooms</h4>
                    <p class="value">{{ $maintenanceRooms }}</p>
                    <div class="note">Under service or repair</div>
                    <div class="premium-kicker">Unavailable rooms</div>
                </div>
            </div>
        </a>
    </div>

    <div class="grid-3">
        <a href="{{ route('bookings.index', ['status' => 'pending']) }}"
           class="dashboard-link">
            <div class="stats-card bg-orange premium-card">
                <div class="premium-top">
                    <div class="premium-icon">P</div>
                    <div class="premium-arrow">→</div>
                </div>
                <div class="premium-content">
                    <h4>Pending Bookings</h4>
                    <p class="value">{{ $pendingBookings }}</p>
                    <div class="note">Waiting for confirmation</div>
                    <div class="premium-kicker">Needs action</div>
                </div>
            </div>
        </a>

        <a href="{{ route('bookings.index', ['status' => 'confirmed']) }}"
           class="dashboard-link">
            <div class="stats-card bg-teal premium-card">
                <div class="premium-top">
                    <div class="premium-icon">C</div>
                    <div class="premium-arrow">→</div>
                </div>
                <div class="premium-content">
                    <h4>Confirmed Bookings</h4>
                    <p class="value">{{ $confirmedBookings }}</p>
                    <div class="note">Approved and scheduled</div>
                    <div class="premium-kicker">Ready arrivals</div>
                </div>
            </div>
        </a>

        <a href="{{ route('bookings.index', ['status' => 'checked_in']) }}"
           class="dashboard-link">
            <div class="stats-card bg-blue premium-card">
                <div class="premium-top">
                    <div class="premium-icon">I</div>
                    <div class="premium-arrow">→</div>
                </div>
                <div class="premium-content">
                    <h4>Checked In</h4>
                    <p class="value">{{ $checkedInBookings }}</p>
                    <div class="note">Guests currently staying</div>
                    <div class="premium-kicker">Live occupancy</div>
                </div>
            </div>
        </a>
    </div>

    <div class="grid-3">
        <a href="{{ route('bookings.index', ['status' => 'checked_out']) }}"
           class="dashboard-link">
            <div class="stats-card bg-purple premium-card">
                <div class="premium-top">
                    <div class="premium-icon">O</div>
                    <div class="premium-arrow">→</div>
                </div>
                <div class="premium-content">
                    <h4>Checked Out</h4>
                    <p class="value">{{ $checkedOutBookings }}</p>
                    <div class="note">Completed stays</div>
                    <div class="premium-kicker">Finished reservations</div>
                </div>
            </div>
        </a>

        <a href="{{ route('bookings.index', ['status' => 'cancelled']) }}"
           class="dashboard-link">
            <div class="stats-card bg-orange premium-card">
                <div class="premium-top">
                    <div class="premium-icon">X</div>
                    <div class="premium-arrow">→</div>
                </div>
                <div class="premium-content">
                    <h4>Cancelled</h4>
                    <p class="value">{{ $cancelledBookings }}</p>
                    <div class="note">Open cancelled bookings</div>
                    <div class="premium-kicker">Closed requests</div>
                </div>
            </div>
        </a>

        <a href="{{ auth()->user()->role === 'admin' ? route('users.roles') : route('dashboard') }}"
           class="dashboard-link">
            <div class="stats-card bg-blue premium-card">
                <div class="premium-top">
                    <div class="premium-icon">U</div>
                    <div class="premium-arrow">→</div>
                </div>
                <div class="premium-content">
                    <h4>Total Staff Users</h4>
                    <p class="value">{{ $totalUsers }}</p>
                    <div class="note">
                        {{ auth()->user()->role === 'admin' ? 'Open access control' : 'Staff count overview' }}
                    </div>
                    <div class="premium-kicker">Team management</div>
                </div>
            </div>
        </a>
    </div>

    <section class="occupancy-forecast"
             aria-labelledby="occupancy-forecast-title">
        <div class="of-header">
            <div>
                <span class="of-eyebrow">OCCUPANCY FORECAST</span>

                <h2 id="occupancy-forecast-title">
                    AI Occupancy Forecast
                </h2>

                <p>
                    Seven-day model output, alongside reservations
                    already in your database.
                </p>
            </div>

            <span class="of-badge">
                Trained on synthetic history
            </span>
        </div>

        @if(session('forecast_success'))
            <div class="alert alert-success" role="status">
                {{ session('forecast_success') }}
            </div>
        @endif

        @if(session('forecast_error'))
            <div class="alert alert-error" role="alert">
                {{ session('forecast_error') }}
            </div>
        @endif

        <div style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px; margin-bottom:20px;">
            <p class="muted" style="margin:0;">
                @if($occupancyForecast['ready'])
                    Last updated: {{ $occupancyForecast['last_updated'] }}
                @else
                    No forecast file is available yet.
                @endif
            </p>

            @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                <form id="forecast-refresh-form"
                      method="POST"
                      action="{{ route('dashboard.forecast.refresh') }}">
                    @csrf

                    <button id="forecast-refresh-button"
                            type="submit"
                            class="btn btn-primary">
                        Refresh Forecast
                    </button>
                </form>
            @endif
        </div>

        <p id="forecast-refresh-status"
           class="muted"
           role="status"
           aria-live="polite"></p>

        @if($occupancyForecast['ready'])
            <div class="of-notice">
                <strong>
                    Training data: synthetic historical stays
                </strong>

                <p>
                    Training source: synthetic historical booking data.
                </p>
            </div>

            @if(!$occupancyForecast['starts_today'])
                <p class="of-dated">
                    Forecast period: {{ $occupancyForecast['period'] }}.
                    Refresh the forecast to export history through yesterday
                    and generate a forecast starting today.
                </p>
            @endif

            @if($occupancyForecast['capacity'] !== $totalRooms)
                <p class="of-dated">
                    The trained model uses
                    {{ $occupancyForecast['capacity'] }} rooms.
                    Your current inventory contains
                    {{ $totalRooms }} rooms.
                </p>
            @endif

            <div class="of-metrics">
                <div>
                    <span>Training history</span>
                    <strong>
                        {{ number_format($occupancyForecast['training_days']) }}
                        <small>days</small>
                    </strong>
                </div>

                <div>
                    <span>Model average error</span>
                    <strong>
                        {{ number_format($occupancyForecast['model_mae'], 2) }}
                        <small>rooms</small>
                    </strong>
                </div>

                <div>
                    <span>Last-week baseline error</span>
                    <strong>
                        {{ number_format($occupancyForecast['baseline_mae'], 2) }}
                        <small>rooms</small>
                    </strong>
                </div>
            </div>

            <div class="of-period">
                <strong>
                    {{ $occupancyForecast['period'] }}
                </strong>

                <span>
                    Lower average error is better.
                    Both errors are measured on synthetic data.
                </span>
            </div>

            <div class="table-responsive of-table"
                 tabindex="0"
                 role="region"
                 aria-label="Seven-day forecast and confirmed reservations">
                <table>
                    <caption class="of-sr-only">
                        Confirmed and checked-in reservations
                        compared with a separate synthetic occupancy forecast.
                    </caption>

                    <thead>
                        <tr>
                            <th scope="col">Date</th>
                            <th scope="col">Confirmed rooms</th>
                            <th scope="col">Predicted rooms</th>
                            <th scope="col">Predicted occupancy</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($occupancyForecast['days'] as $day)
                            <tr>
                                <th scope="row">
                                    <time datetime="{{ $day['date'] }}">
                                        {{ $day['label'] }}
                                    </time>
                                </th>

                                <td>
                                    {{ $day['confirmed_rooms'] }}
                                </td>

                                <td>
                                    {{ number_format($day['expected_rooms'], 2) }}

                                    <span class="of-muted">
                                        / {{ $occupancyForecast['capacity'] }}
                                    </span>
                                </td>

                                <td>
                                    <div class="of-percent">
                                        <span>
                                            {{ number_format($day['percent'], 2) }}%
                                        </span>

                                        <meter class="of-meter"
                                               min="0"
                                               max="100"
                                               value="{{ $day['percent'] }}"
                                               aria-hidden="true">
                                            {{ number_format($day['percent'], 2) }}%
                                        </meter>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <p class="of-footnote">
                Training dates:
                {{ $occupancyForecast['training_period'] }}.
                Expected rooms can be fractional.
                Checkout day is excluded from the confirmed-room
                counts. Pending holds are excluded.
            </p>
        @else
            <div class="of-empty">
                <strong>No forecast available</strong>
                <p>{{ $occupancyForecast['message'] }}</p>
            </div>
        @endif
    </section>

    <div class="section-shell">
        <div class="card-header"
             style="padding:0; margin-bottom:18px;">
            <div>
                <span class="page-title-badge">QUICK ACTIONS</span>
                <h2>Operate Faster</h2>
                <p class="muted">
                    Direct shortcuts for the most common
                    front-desk and admin tasks.
                </p>
            </div>
        </div>

        <div class="quick-actions-grid">
            <a href="{{ route('rooms.create') }}"
               class="action-tile action-primary">
                + Add Room
            </a>

            <a href="{{ route('guests.create') }}"
               class="action-tile action-blue">
                + Add Guest
            </a>

            <a href="{{ route('bookings.create') }}"
               class="action-tile action-primary">
                + Create Booking
            </a>

            <a href="{{ route('public.book') }}"
               class="action-tile action-gold">
                Public Booking Page
            </a>

            @if(auth()->user()->role === 'admin')
                <a href="{{ route('users.create') }}"
                   class="action-tile action-dark">
                    + Create User
                </a>

                <a href="{{ route('users.roles') }}"
                   class="action-tile action-blue">
                    Manage User Roles
                </a>

                <a href="{{ route('finance.index') }}"
                   class="action-tile action-gold">
                    Finance Dashboard
                </a>

                <a href="{{ route('expenses.index') }}"
                   class="action-tile action-dark">
                    Manage Expenses
                </a>

                <a href="{{ route('expenses.create') }}"
                   class="action-tile action-primary">
                    + Add Expense
                </a>
            @endif
        </div>
    </div>

    <div class="premium-subgrid">
        <div class="mini-summary">
            <h4>Priority Queue</h4>
            <p>{{ $pendingBookings }} pending</p>
        </div>

        <div class="mini-summary">
            <h4>Current Occupancy</h4>
            <p>{{ $occupiedRooms }} occupied</p>
        </div>

        <div class="mini-summary">
            <h4>Active Stays</h4>
            <p>{{ $checkedInBookings }} checked in</p>
        </div>
    </div>

    <div class="grid-2">
        <div class="soft-table-wrap">
            <div class="card-header"
                 style="padding:0; margin-bottom:18px;">
                <div>
                    <span class="page-title-badge">LATEST BOOKINGS</span>
                    <h2>Recent Reservations</h2>
                    <p class="muted">
                        Newest booking activity across the resort.
                    </p>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Room</th>
                        <th>Guest</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($latestBookings as $booking)
                        <tr>
                            <td>#{{ $booking->id }}</td>

                            <td>
                                <strong>
                                    {{ $booking->room->number }}
                                </strong><br>

                                <span class="muted">
                                    {{ $booking->room->name }}
                                </span>
                            </td>

                            <td>
                                <strong>
                                    {{ $booking->guest->full_name }}
                                </strong><br>

                                <span class="muted">
                                    {{ $booking->check_in }}
                                    →
                                    {{ $booking->check_out }}
                                </span>
                            </td>

                            <td>
                                <span class="status status-{{ $booking->status }}">
                                    {{ str_replace('_', ' ', $booking->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="muted">
                                No bookings found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="soft-table-wrap">
            <div class="card-header"
                 style="padding:0; margin-bottom:18px;">
                <div>
                    <span class="page-title-badge">LATEST GUESTS</span>
                    <h2>Newest Guest Profiles</h2>
                    <p class="muted">
                        Recently added guest records.
                    </p>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Guest Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($latestGuests as $guest)
                        <tr>
                            <td>#{{ $guest->id }}</td>

                            <td>
                                <strong>
                                    {{ $guest->full_name }}
                                </strong>
                            </td>

                            <td>{{ $guest->phone }}</td>
                            <td>{{ $guest->email ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="muted">
                                No guests found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        (() => {
            const form = document.getElementById('forecast-refresh-form');
            const button = document.getElementById('forecast-refresh-button');
            const status = document.getElementById('forecast-refresh-status');

            if (!form || !button || !status) {
                return;
            }

            form.addEventListener('submit', (event) => {
                if (form.dataset.running === 'true') {
                    event.preventDefault();
                    return;
                }

                form.dataset.running = 'true';
                form.setAttribute('aria-busy', 'true');

                button.disabled = true;
                button.textContent = 'Refreshing…';

                status.textContent =
                    'Exporting history and updating the forecast. '
                    + 'Please keep this page open.';
            });

            window.addEventListener('pageshow', () => {
                form.dataset.running = 'false';
                form.removeAttribute('aria-busy');

                button.disabled = false;
                button.textContent = 'Refresh Forecast';

                status.textContent = '';
            });
        })();
    </script>
</x-app-layout>