<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resort Management System</title>
    <style>
        * {
            box-sizing: border-box;
        }

        :root {
            --bg: #f4f7fb;
            --card: #ffffff;
            --text: #172033;
            --muted: #6b7280;
            --line: #e6ebf2;
            --primary: #0f766e;
            --primary-dark: #0b5d57;
            --secondary: #1d4ed8;
            --danger: #dc2626;
            --warning: #d97706;
            --success: #16a34a;
            --shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            --radius: 18px;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(180deg, #eef6ff 0%, #f8fbff 35%, #f4f7fb 100%);
            color: var(--text);
        }

        a {
            text-decoration: none;
        }

        .app-shell {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 270px;
            background: linear-gradient(180deg, #0f172a 0%, #10243b 100%);
            color: #fff;
            padding: 28px 20px;
            position: sticky;
            top: 0;
            height: 100vh;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
        }

        .brand-mark {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: linear-gradient(135deg, #14b8a6, #2563eb);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 700;
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);
        }

        .brand h1 {
            margin: 0;
            font-size: 18px;
            line-height: 1.2;
        }

        .brand p {
            margin: 2px 0 0;
            color: rgba(255,255,255,0.72);
            font-size: 12px;
        }

        .profile-box {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 18px;
            padding: 16px;
            margin-bottom: 24px;
        }

        .profile-box .role-badge {
            display: inline-block;
            margin-top: 8px;
            padding: 6px 12px;
            border-radius: 999px;
            background: rgba(20, 184, 166, 0.14);
            color: #99f6e4;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .nav-title {
            font-size: 12px;
            color: rgba(255,255,255,0.58);
            margin: 18px 0 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .nav a {
            color: rgba(255,255,255,0.86);
            padding: 12px 14px;
            border-radius: 14px;
            transition: 0.2s ease;
            font-weight: 600;
        }

        .nav a:hover {
            background: rgba(255,255,255,0.10);
            transform: translateX(3px);
        }

        .logout-form {
            margin-top: 24px;
        }

        .logout-btn {
            width: 100%;
            border: none;
            cursor: pointer;
            padding: 12px 14px;
            border-radius: 14px;
            background: rgba(220, 38, 38, 0.16);
            color: #fecaca;
            font-weight: 700;
        }

        .main {
            flex: 1;
            padding: 28px;
        }

        .topbar {
            background: rgba(255,255,255,0.75);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(230,235,242,0.9);
            box-shadow: var(--shadow);
            border-radius: 20px;
            padding: 18px 22px;
            margin-bottom: 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
        }

        .topbar h2 {
            margin: 0;
            font-size: 24px;
        }

        .topbar p {
            margin: 4px 0 0;
            color: var(--muted);
            font-size: 14px;
        }

        .page-content {
            display: flex;
            flex-direction: column;
            gap: 22px;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--line);
            box-shadow: var(--shadow);
            border-radius: var(--radius);
            padding: 22px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
        }

        .card-header h3,
        .card-header h2 {
            margin: 0;
        }

        .muted {
            color: var(--muted);
        }

        .btn {
            display: inline-block;
            border: none;
            cursor: pointer;
            padding: 11px 16px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            transition: 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), #0ea5a4);
            color: #fff;
        }

        .btn-secondary {
            background: #e8eefc;
            color: #1e3a8a;
        }

        .btn-success {
            background: #e8f8ee;
            color: #166534;
        }

        .btn-warning {
            background: #fff4e5;
            color: #b45309;
        }

        .btn-danger {
            background: #fde8e8;
            color: #b91c1c;
        }

        .btn-dark {
            background: #0f172a;
            color: #fff;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .stats-card {
            padding: 20px;
            border-radius: 18px;
            color: #fff;
            box-shadow: var(--shadow);
        }

        .stats-card h4 {
            margin: 0 0 10px;
            font-size: 14px;
            opacity: 0.92;
        }

        .stats-card .value {
            font-size: 30px;
            font-weight: 800;
            margin: 0;
        }

        .stats-card .note {
            margin-top: 8px;
            font-size: 13px;
            opacity: 0.85;
        }

        .bg-teal {
            background: linear-gradient(135deg, #0f766e, #14b8a6);
        }

        .bg-blue {
            background: linear-gradient(135deg, #1d4ed8, #60a5fa);
        }

        .bg-orange {
            background: linear-gradient(135deg, #c2410c, #fb923c);
        }

        .bg-purple {
            background: linear-gradient(135deg, #6d28d9, #a78bfa);
        }

        .alert {
            padding: 14px 16px;
            border-radius: 14px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .alert-success {
            background: #ecfdf3;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .alert-info {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1d4ed8;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            font-size: 13px;
            color: var(--muted);
            padding: 14px 12px;
            border-bottom: 1px solid var(--line);
            background: #f8fafc;
        }

        td {
            padding: 14px 12px;
            border-bottom: 1px solid #edf2f7;
            vertical-align: top;
        }

        tr:hover td {
            background: #fbfdff;
        }

        .status {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .status-pending { background: #fff7ed; color: #c2410c; }
        .status-confirmed { background: #ecfeff; color: #0f766e; }
        .status-checked_in { background: #eff6ff; color: #1d4ed8; }
        .status-checked_out { background: #f3f4f6; color: #374151; }
        .status-cancelled { background: #fef2f2; color: #b91c1c; }
        .status-available { background: #ecfdf3; color: #15803d; }
        .status-occupied { background: #eff6ff; color: #1d4ed8; }
        .status-maintenance { background: #fff7ed; color: #b45309; }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .actions form {
            display: inline-block;
            margin: 0;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            font-size: 14px;
            font-weight: 700;
            color: #1f2937;
        }

        input, select, textarea {
            width: 100%;
            border: 1px solid #dbe3ef;
            border-radius: 14px;
            padding: 13px 14px;
            font-size: 14px;
            background: #fff;
            color: var(--text);
            outline: none;
            transition: 0.2s ease;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #7dd3fc;
            box-shadow: 0 0 0 4px rgba(125, 211, 252, 0.20);
        }

        .field-error {
            color: #dc2626;
            font-size: 13px;
            margin-top: 4px;
        }

        .detail-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .detail-item {
            background: #f8fbff;
            border: 1px solid #e5edf7;
            border-radius: 16px;
            padding: 16px;
        }

        .detail-item h4 {
            margin: 0 0 6px;
            font-size: 13px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-item p {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
        }

        .page-title-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            background: #e0f2fe;
            color: #075985;
            font-size: 12px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        @media (max-width: 1024px) {
            .sidebar {
                display: none;
            }

            .main {
                padding: 18px;
            }

            .grid-3,
            .grid-2,
            .form-grid,
            .detail-list {
                grid-template-columns: 1fr;
            }

            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-mark">R</div>
                <div>
                    <h1>Resort Manager</h1>
                    <p>McLovin Resort Control Panel</p>
                </div>
            </div>

            @auth
                <div class="profile-box">
                    <div style="font-size: 12px; color: rgba(255,255,255,0.66);">Logged in as</div>
                    <div style="font-size: 18px; font-weight: 800; margin-top: 6px;">{{ auth()->user()->name }}</div>
                    <div style="font-size: 13px; color: rgba(255,255,255,0.72); margin-top: 4px;">{{ auth()->user()->email }}</div>
                    <span class="role-badge">{{ auth()->user()->role }}</span>
                </div>
            @endauth

            <div class="nav-title">Main Menu</div>

            <nav class="nav">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('rooms.index') }}">Rooms</a>
                <a href="{{ route('guests.index') }}">Guests</a>
                <a href="{{ route('bookings.index') }}">Bookings</a>
                <a href="{{ route('public.book') }}">Public Booking</a>

                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('users.roles') }}">User Roles</a>
                @endif
            </nav>

            @auth
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button class="logout-btn" type="submit">Logout</button>
                </form>
            @endauth
        </aside>

        <main class="main">
            <div class="topbar">
                <div>
                    <h2>Resort Management System</h2>
                    <p>Modern control panel for rooms, guests, bookings, and staff roles.</p>
                </div>
                <div>
                    <span class="page-title-badge">Sajek, Bangladesh</span>
                </div>
            </div>

            <div class="page-content">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>