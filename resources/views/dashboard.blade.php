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
            transition: transform 0.25s ease, box-shadow 0.25s ease, filter 0.25s ease;
        }

        .premium-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 24px 48px rgba(15, 23, 42, 0.20);
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
            background: rgba(255,255,255,0.12);
        }

        .premium-card::after {
            content: "";
            position: absolute;
            bottom: -26px;
            left: -26px;
            width: 90px;
            height: 90px;
            border-radius: 999px;
            background: rgba(255,255,255,0.08);
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
            background: rgba(255,255,255,0.08);
            font-size: 13px;
            font-weight: 700;
            opacity: 0.9;
        }

        .premium-arrow {
            position: relative;
            z-index: 2;
            font-size: 16px;
            font-weight: 900;
            opacity: 0.85;
            transition: transform 0.2s ease;
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
            background: rgba(255,255,255,0.12);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.3px;
        }

        .premium-subgrid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .mini-summary {
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            border: 1px solid #e6edf5;
            border-radius: 20px;
            padding: 18px;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
        }

        .mini-summary h4 {
            margin: 0 0 8px;
            color: #64748b;
            font-size: 13px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .mini-summary p {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
        }

        .section-shell {
            background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
            border: 1px solid #e7eef6;
            border-radius: 24px;
            padding: 22px;
            box-shadow: 0 16px 36px rgba(15, 23, 42, 0.06);
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
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .action-tile:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 24px rgba(15, 23, 42, 0.12);
        }

        .action-primary {
            background: linear-gradient(135deg, #0f766e, #14b8a6);
            color: #fff;
        }

        .action-blue {
            background: linear-gradient(135deg, #1d4ed8, #60a5fa);
            color: #fff;
        }

        .action-gold {
            background: linear-gradient(135deg, #c9971a, #f0cf76);
            color: #231800;
        }

        .action-dark {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: #fff;
        }

        .soft-table-wrap {
            background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
            border: 1px solid #e7eef6;
            border-radius: 24px;
            padding: 22px;
            box-shadow: 0 16px 36px rgba(15, 23, 42, 0.06);
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

        @media (max-width: 1024px) {
            .premium-subgrid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="headline-note">Live Resort Control Overview</div>

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
        <a href="{{ route('rooms.index', ['status' => 'available']) }}" class="dashboard-link">
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

        <a href="{{ route('rooms.index', ['status' => 'occupied']) }}" class="dashboard-link">
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

        <a href="{{ route('rooms.index', ['status' => 'maintenance']) }}" class="dashboard-link">
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
        <a href="{{ route('bookings.index', ['status' => 'pending']) }}" class="dashboard-link">
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

        <a href="{{ route('bookings.index', ['status' => 'confirmed']) }}" class="dashboard-link">
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

        <a href="{{ route('bookings.index', ['status' => 'checked_in']) }}" class="dashboard-link">
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
        <a href="{{ route('bookings.index', ['status' => 'checked_out']) }}" class="dashboard-link">
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

        <a href="{{ route('bookings.index', ['status' => 'cancelled']) }}" class="dashboard-link">
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

        <a href="{{ auth()->user()->role === 'admin' ? route('users.roles') : route('dashboard') }}" class="dashboard-link">
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

    <div class="section-shell">
        <div class="card-header" style="padding: 0; margin-bottom: 18px;">
            <div>
                <span class="page-title-badge">QUICK ACTIONS</span>
                <h2>Operate Faster</h2>
                <p class="muted">Direct shortcuts for the most common front-desk and admin tasks.</p>
            </div>
        </div>

        <div class="quick-actions-grid">
            <a href="{{ route('rooms.create') }}" class="action-tile action-primary">+ Add Room</a>
            <a href="{{ route('guests.create') }}" class="action-tile action-blue">+ Add Guest</a>
            <a href="{{ route('bookings.create') }}" class="action-tile action-primary">+ Create Booking</a>
            <a href="{{ route('public.book') }}" class="action-tile action-gold">Public Booking Page</a>

            @if(auth()->user()->role === 'admin')
                <a href="{{ route('users.create') }}" class="action-tile action-dark">+ Create User</a>
                <a href="{{ route('users.roles') }}" class="action-tile action-blue">Manage User Roles</a>
                <a href="{{ route('finance.index') }}" class="action-tile action-gold">Finance Dashboard</a>
                <a href="{{ route('expenses.index') }}" class="action-tile action-dark">Manage Expenses</a>
                <a href="{{ route('expenses.create') }}" class="action-tile action-primary">+ Add Expense</a>
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
            <div class="card-header" style="padding: 0; margin-bottom: 18px;">
                <div>
                    <span class="page-title-badge">LATEST BOOKINGS</span>
                    <h2>Recent Reservations</h2>
                    <p class="muted">Newest booking activity across the resort.</p>
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
                                <strong>{{ $booking->room->number }}</strong><br>
                                <span class="muted">{{ $booking->room->name }}</span>
                            </td>
                            <td>
                                <strong>{{ $booking->guest->full_name }}</strong><br>
                                <span class="muted">{{ $booking->check_in }} → {{ $booking->check_out }}</span>
                            </td>
                            <td>
                                <span class="status status-{{ $booking->status }}">
                                    {{ str_replace('_', ' ', $booking->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="muted">No bookings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="soft-table-wrap">
            <div class="card-header" style="padding: 0; margin-bottom: 18px;">
                <div>
                    <span class="page-title-badge">LATEST GUESTS</span>
                    <h2>Newest Guest Profiles</h2>
                    <p class="muted">Recently added guest records.</p>
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
                            <td><strong>{{ $guest->full_name }}</strong></td>
                            <td>{{ $guest->phone }}</td>
                            <td>{{ $guest->email ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="muted">No guests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>