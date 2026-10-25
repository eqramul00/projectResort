@php
    /** @var \App\Models\User|null $staff */
    $staff = auth()->user();

    $pageTitle = match (true) {
        request()->routeIs('dashboard') => 'Dashboard',
        request()->routeIs('stays.*') => 'Stay Management',
        request()->routeIs('rooms.*') => 'Room Management',
        request()->routeIs('guests.*') => 'Guest Management',
        request()->routeIs('maintenance.*') => 'Maintenance',
        request()->routeIs('bookings.calendar') => 'Booking Calendar',
        request()->routeIs('bookings.*') => 'Booking Management',
        request()->routeIs('finance.*') => 'Finance Overview',
        request()->routeIs('expenses.*') => 'Expense Management',
        request()->routeIs('users.*') => 'Staff Administration',
        default => 'Resort Management',
    };

    $navigation = [
        [
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'pattern' => 'dashboard',
            'icon' => 'dashboard',
            'group' => 'Workspace',
        ],
        [
            'label' => 'Bookings',
            'route' => 'bookings.index',
            'pattern' => 'bookings.*',
            'icon' => 'calendar',
            'group' => 'Workspace',
        ],
        [
            'label' => 'Stay Management',
            'route' => 'stays.index',
            'pattern' => 'stays.*',
            'icon' => 'room',
            'group' => 'Workspace',
        ],
        [
            'label' => 'Rooms',
            'route' => 'rooms.index',
            'pattern' => 'rooms.*',
            'icon' => 'room',
            'group' => 'Workspace',
        ],
        [
            'label' => 'Guests',
            'route' => 'guests.index',
            'pattern' => 'guests.*',
            'icon' => 'users',
            'group' => 'Workspace',
        ],
        [
            'label' => 'Maintenance',
            'route' => 'maintenance.index',
            'pattern' => 'maintenance.*',
            'icon' => 'tool',
            'group' => 'Operations',
        ],
        [
            'label' => 'Finance',
            'route' => 'finance.index',
            'pattern' => 'finance.*',
            'icon' => 'chart',
            'group' => 'Operations',
        ],
        [
            'label' => 'Expenses',
            'route' => 'expenses.index',
            'pattern' => 'expenses.*',
            'icon' => 'receipt',
            'group' => 'Operations',
        ],
    ];

    if ($staff && $staff->role === 'admin') {
        $navigation[] = [
            'label' => 'Staff & Roles',
            'route' => 'users.roles',
            'pattern' => 'users.*',
            'icon' => 'shield',
            'group' => 'Administration',
        ];
    }

    $initial = $staff
        ? mb_strtoupper(mb_substr($staff->name, 0, 1))
        : 'R';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $pageTitle }} | McLovin Resort</title>

    <script>
        document.documentElement.classList.add('js');
    </script>

    <style>
        * { box-sizing: border-box; }

        :root {
            --bg: #f4f6f8;
            --card: #ffffff;
            --text: #172b28;
            --muted: #687974;
            --line: #e2e8e4;
            --primary: #173c32;
            --primary-dark: #102b24;
            --secondary: #1d4ed8;
            --danger: #b91c1c;
            --warning: #b45309;
            --success: #15803d;
            --shadow: 0 6px 24px rgba(20,45,35,.04);
            --radius: 18px;
            --sidebar-width: 264px;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.5;
        }

        a { color: inherit; text-decoration: none; }
        button, input, select, textarea { font-family: inherit; }
        button, a { -webkit-tap-highlight-color: transparent; }
        img { max-width: 100%; }
        [hidden] { display: none !important; }
        :focus-visible { outline: 3px solid #bc8b35; outline-offset: 3px; }

        .skip-link {
            position: fixed;
            top: -100px;
            left: 16px;
            z-index: 300;
            padding: 12px 18px;
            border-radius: 8px;
            background: #ffffff;
            color: var(--primary);
            font-weight: 700;
        }

        .skip-link:focus { top: 12px; }

        .icon {
            display: block;
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.7;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .app-shell {
            display: flex;
            min-height: 100vh;
            min-height: 100dvh;
        }

        .sidebar {
            width: var(--sidebar-width);
            flex: 0 0 var(--sidebar-width);
            position: sticky;
            top: 0;
            height: 100vh;
            height: 100dvh;
            display: flex;
            flex-direction: column;
            padding: 26px 18px 18px;
            background: var(--primary-dark);
            color: #ffffff;
            overflow-y: auto;
            overscroll-behavior: contain;
            scrollbar-width: thin;
            scrollbar-color: #436052 transparent;
        }

        .sidebar-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 27px;
        }

        .brand { display: flex; align-items: center; gap: 11px; min-width: 0; }

        .brand-mark {
            display: grid;
            place-items: center;
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            border: 1px solid rgba(223,191,122,.35);
            border-radius: 12px;
            background: rgba(223,191,122,.12);
            color: #ead09a;
            font-family: Georgia, serif;
            font-size: 24px;
        }

        .brand-name {
            display: block;
            font-family: Georgia, serif;
            font-size: 21px;
            font-weight: 400;
            line-height: 1.2;
        }

        .brand-subtitle {
            display: block;
            margin-top: 5px;
            color: #9fb6a8;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1.7px;
        }

        .sidebar-close,
        .menu-button {
            display: none;
            place-items: center;
            width: 42px;
            height: 42px;
            padding: 0;
            flex-shrink: 0;
            border: 1px solid var(--line);
            border-radius: 10px;
            cursor: pointer;
        }

        .sidebar-close {
            background: rgba(255,255,255,.06);
            color: #ffffff;
            border-color: rgba(255,255,255,.15);
        }

        .sidebar-nav { display: flex; flex-direction: column; gap: 5px; }

        .nav-title {
            margin: 23px 12px 9px;
            color: #9ab3a5;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.7px;
        }

        .nav-title:first-child { margin-top: 0; }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 46px;
            padding: 11px 13px;
            border-radius: 10px;
            color: #c2d2c8;
            font-size: 13px;
            font-weight: 600;
            transition: background .18s ease, color .18s ease;
        }

        .sidebar-link:hover {
            color: #ffffff;
            background: rgba(255,255,255,.06);
        }

        .sidebar-link.active {
            color: #ffffff;
            background: #2b5040;
            box-shadow: inset 3px 0 0 #dfbf7a;
        }

        .sidebar-link .icon { opacity: .85; }
        .sidebar-link.active .icon { color: #ead09a; opacity: 1; }

        .sidebar-footer { margin-top: auto; padding-top: 28px; }
        .website-link { margin-bottom: 18px; border: 1px solid #365244; }
        .website-link .external-icon { width: 15px; height: 15px; margin-left: auto; }

        .profile-box { padding: 16px 8px 12px; border-top: 1px solid #365244; }
        .profile-row { display: flex; align-items: center; gap: 10px; }

        .avatar {
            display: grid;
            place-items: center;
            width: 36px;
            height: 36px;
            flex-shrink: 0;
            border-radius: 50%;
            background: #e0c48b;
            color: #183629;
            font-size: 14px;
            font-weight: 700;
        }

        .profile-details { min-width: 0; }

        .profile-name {
            display: block;
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .profile-email {
            display: block;
            margin-top: 3px;
            color: #a7beb0;
            font-size: 10px;
            overflow-wrap: anywhere;
        }

        .role-badge {
            display: inline-block;
            margin-top: 10px;
            padding: 4px 8px;
            border: 1px solid #436353;
            border-radius: 6px;
            color: #c9ddcc;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .logout-form { margin: 8px 0 0; }

        .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            width: 100%;
            min-height: 43px;
            padding: 10px;
            border: 1px solid #4e5143;
            border-radius: 9px;
            background: rgba(255,255,255,.035);
            color: #e3cfbc;
            cursor: pointer;
            font-size: 12px;
            font-weight: 700;
        }

        .logout-btn:hover { background: rgba(255,255,255,.08); }
        .sidebar-backdrop { display: none; }

        .main { flex: 1; min-width: 0; display: flex; flex-direction: column; }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            min-height: 91px;
            padding: 19px 32px;
            background: #ffffff;
            border-bottom: 1px solid var(--line);
        }

        .topbar-leading { display: flex; align-items: center; gap: 14px; min-width: 0; }

        .breadcrumb {
            margin: 0 0 4px;
            color: #7a8981;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }

        .topbar h1 {
            margin: 0;
            font-size: 22px;
            line-height: 1.3;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .topbar-meta { display: flex; align-items: center; gap: 16px; flex-shrink: 0; }
        .topbar-date { color: var(--muted); font-size: 12px; }

        .topbar-role {
            padding: 7px 11px;
            border: 1px solid #dce5d7;
            border-radius: 8px;
            background: #f0f4ec;
            color: #3c5b38;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
        }

        .page-content {
            display: flex;
            flex-direction: column;
            gap: 22px;
            flex: 1;
            min-width: 0;
            padding: 28px 32px;
        }

        .page-content > * { min-width: 0; max-width: 100%; }

        .app-footer {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            padding: 18px 32px;
            border-top: 1px solid var(--line);
            color: #75827b;
            font-size: 10px;
        }

        .card {
            min-width: 0;
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
            gap: 16px;
            margin-bottom: 20px;
        }

        .card-header h2, .card-header h3 { margin: 0; line-height: 1.3; }
        .card-header .muted { margin-bottom: 0; }
        .muted { color: var(--muted); }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            min-height: 42px;
            border: 1px solid transparent;
            cursor: pointer;
            padding: 10px 15px;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 700;
            text-align: center;
            line-height: 1.4;
            transition: filter .18s ease;
        }

        .btn:hover { filter: brightness(.96); }
        .btn:disabled { opacity: .55; cursor: not-allowed; }

        .btn-primary { background: var(--primary); color: #ffffff; }
        .btn-secondary { background: #edf1f5; color: #35475b; }
        .btn-success { background: #e9f5e9; color: #28612d; }
        .btn-warning { background: #fff2dd; color: #92590f; }
        .btn-danger { background: #fcebea; color: #a3312b; }
        .btn-dark { background: #25372f; color: #ffffff; }

        .grid-2, .grid-3, .grid-4, .form-grid, .detail-list {
            display: grid;
            gap: 18px;
        }

        .grid-2, .form-grid, .detail-list {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .grid-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .grid-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }

        .stats-card {
            min-width: 0;
            padding: 22px;
            border-radius: 17px;
            color: #ffffff;
            box-shadow: var(--shadow);
        }

        .stats-card h4 { margin: 0 0 12px; font-size: 13px; font-weight: 500; }
        .stats-card .value { margin: 0; font-size: 31px; font-weight: 700; overflow-wrap: anywhere; }
        .stats-card .note { margin-top: 10px; font-size: 12px; opacity: .9; }

        .bg-teal { background: linear-gradient(135deg, #245546, #38816b); }
        .bg-blue { background: linear-gradient(135deg, #31547e, #537caf); }
        .bg-orange { background: linear-gradient(135deg, #9e5b2a, #cf8d4b); }
        .bg-purple { background: linear-gradient(135deg, #675285, #977ead); }
        .bg-red { background: linear-gradient(135deg, #9b3a34, #c7645d); }

        .alert {
            padding: 14px 16px;
            border-radius: 11px;
            margin-bottom: 16px;
            font-size: 13px;
            line-height: 1.7;
        }

        .alert-success { background: #edf7ec; border: 1px solid #c7e1c3; color: #315d2c; }
        .alert-error { background: #fff0ed; border: 1px solid #efc9c0; color: #942e24; }
        .alert-info { background: #eef4fb; border: 1px solid #ccdcef; color: #325a86; }
        .alert-warning { background: #fff7e7; border: 1px solid #ebd6a2; color: #865f18; }

        .app-table-scroll,
        .table-responsive {
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
            overscroll-behavior-x: contain;
            -webkit-overflow-scrolling: touch;
        }

        table { width: 100%; border-collapse: collapse; }

        th {
            padding: 14px 12px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: var(--muted);
            border-bottom: 1px solid var(--line);
            background: #f7f9f6;
        }

        td {
            padding: 14px 12px;
            border-bottom: 1px solid #edf1eb;
            vertical-align: top;
            font-size: 13px;
        }

        tbody tr:hover td { background: #fafcf8; }

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 7px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .3px;
            white-space: nowrap;
        }

        .status-pending, .status-maintenance, .status-open {
            background: #fff3df;
            color: #92590f;
        }

        .status-confirmed, .status-available, .status-verified, .status-resolved {
            background: #eaf4e7;
            color: #35652b;
        }

        .status-checked_in, .status-occupied, .status-submitted, .status-in_progress {
            background: #eaf1fb;
            color: #365d8d;
        }

        .status-checked_out { background: #edf0ed; color: #526057; }
        .status-cancelled, .status-rejected { background: #fcebe7; color: #a13b2d; }

        .actions { display: flex; align-items: center; flex-wrap: wrap; gap: 8px; }
        .actions form { margin: 0; }
        .form-group { display: flex; flex-direction: column; gap: 8px; min-width: 0; }
        .form-group.full, .full { grid-column: 1 / -1; }

        label { font-size: 13px; font-weight: 700; color: #304237; }

        input,
        select,
        textarea {
            width: 100%;
            min-width: 0;
            min-height: 46px;
            border: 1px solid #cfd9cf;
            border-radius: 10px;
            padding: 12px 13px;
            font-size: 14px;
            background: #ffffff;
            color: var(--text);
        }

        input[type="checkbox"],
        input[type="radio"] {
            width: 18px;
            min-width: 18px;
            min-height: 18px;
            height: 18px;
            padding: 0;
            accent-color: var(--primary);
        }

        input:focus, select:focus, textarea:focus { border-color: #62866a; }
        textarea { resize: vertical; }
        .field-error { color: #a3312b; font-size: 12px; margin-top: 4px; }

        .detail-item {
            min-width: 0;
            background: #f7f9f5;
            border: 1px solid #e2e8de;
            border-radius: 12px;
            padding: 17px;
        }

        .detail-item h4 {
            margin: 0 0 7px;
            color: var(--muted);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .7px;
        }

        .detail-item p {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .page-title-badge {
            display: inline-block;
            margin-bottom: 11px;
            padding: 5px 9px;
            border-radius: 6px;
            background: #edf2e7;
            color: #527044;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        @media (max-width: 1200px) {
            :root { --sidebar-width: 240px; }
            .topbar { padding-inline: 24px; }
            .page-content { padding: 24px; }
            .grid-4 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 1024px) {
            .app-shell { display: block; }

            .sidebar {
                position: static;
                width: 100%;
                height: auto;
                padding: 20px;
            }

            .js .sidebar {
                position: fixed;
                z-index: 120;
                inset: 0 auto 0 0;
                width: min(300px, calc(100vw - 48px));
                height: 100vh;
                height: 100dvh;
                transform: translateX(-105%);
                visibility: hidden;
                transition: transform .22s ease, visibility .22s ease;
                padding-bottom: calc(18px + env(safe-area-inset-bottom));
            }

            .js body.sidebar-open .sidebar {
                transform: translateX(0);
                visibility: visible;
            }

            .js body.sidebar-open { overflow: hidden; }
            .js .menu-button, .js .sidebar-close { display: grid; }
            .menu-button { background: #f3f6f0; color: var(--primary); }

            .sidebar-backdrop:not([hidden]) {
                display: block;
                position: fixed;
                z-index: 110;
                inset: 0;
                width: 100%;
                height: 100%;
                border: 0;
                background: rgba(8,22,16,.5);
                cursor: pointer;
            }

            .topbar { min-height: 80px; padding: 16px 20px; }
            .topbar-date { display: none; }
            .topbar h1 { font-size: 20px; }
            .page-content { padding: 20px; }
            .sidebar-footer { padding-top: 20px; }
        }

        @media (max-width: 760px) {
            .grid-3, .grid-4 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .card-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .topbar-meta { display: none; }
            .breadcrumb { font-size: 8px; }
            .app-footer { padding-inline: 20px; }
        }

        @media (max-width: 560px) {
            .page-content { padding: 16px; gap: 16px; }
            .topbar { padding: 14px 16px; }
            .topbar h1 { font-size: 18px; }
            .topbar-leading { gap: 11px; }
            .card { padding: 18px; border-radius: 14px; }

            .grid-2, .grid-3, .grid-4, .form-grid, .detail-list {
                grid-template-columns: 1fr;
            }

            .stats-card { padding: 19px; }
            .stats-card .value { font-size: 28px; }
            .btn { min-height: 44px; }
            input, select, textarea { font-size: 16px; }
            .detail-item p { font-size: 15px; }
            .card-header > .actions { width: 100%; }
            .card-header > .actions > .btn { flex: 1 1 auto; }
            th, td { padding: 12px 10px; }
            .app-footer { padding: 16px; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                transition: none !important;
            }
        }
    </style>
</head>

<body>
    <a href="#pageContent" class="skip-link">Skip to page content</a>

    <svg
        xmlns="http://www.w3.org/2000/svg"
        style="position:absolute; width:0; height:0; overflow:hidden;"
        aria-hidden="true"
    >
        <symbol id="icon-dashboard" viewBox="0 0 24 24">
            <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
            <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
            <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
            <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
        </symbol>

        <symbol id="icon-calendar" viewBox="0 0 24 24">
            <rect x="3" y="5" width="18" height="16" rx="2"></rect>
            <path d="M16 3v4M8 3v4M3 11h18M8 15h2M14 15h2"></path>
        </symbol>

        <symbol id="icon-room" viewBox="0 0 24 24">
            <path d="M3 18v-7M21 18v-7M3 15h18M5 11V6h14v5"></path>
            <path d="M3 11h18v4H3zM8 8h3M14 8h2"></path>
        </symbol>

        <symbol id="icon-users" viewBox="0 0 24 24">
            <circle cx="9" cy="8" r="3"></circle>
            <path d="M3 21v-3a6 6 0 0 1 12 0v3M16 5a3 3 0 0 1 0 6M21 21v-3a6 6 0 0 0-4-5.65"></path>
        </symbol>

        <symbol id="icon-tool" viewBox="0 0 24 24">
            <path d="M14.5 6.5l3 3M14 3a6 6 0 0 0-5 8L3.5 16.5a2.8 2.8 0 0 0 4 4L13 15a6 6 0 0 0 8-5l-4 2-5-5 2-4z"></path>
        </symbol>

        <symbol id="icon-chart" viewBox="0 0 24 24">
            <path d="M3 3v18h18M7 16v-4M12 16V8M17 16V5"></path>
        </symbol>

        <symbol id="icon-receipt" viewBox="0 0 24 24">
            <path d="M6 3l3 2 3-2 3 2 3-2v18l-3-2-3 2-3-2-3 2V3zM9 9h6M9 13h6"></path>
        </symbol>

        <symbol id="icon-shield" viewBox="0 0 24 24">
            <path d="M12 3l8 3v6c0 5-8 9-8 9s-8-4-8-9V6l8-3z"></path>
            <path d="M8 12l3 3 5-6"></path>
        </symbol>

        <symbol id="icon-external" viewBox="0 0 24 24">
            <path d="M14 3h7v7M21 3L10 14M10 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-5"></path>
        </symbol>

        <symbol id="icon-logout" viewBox="0 0 24 24">
            <path d="M9 4H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h4M14 8l4 4-4 4M8 12h13"></path>
        </symbol>

        <symbol id="icon-menu" viewBox="0 0 24 24">
            <path d="M4 6h16M4 12h16M4 18h16"></path>
        </symbol>

        <symbol id="icon-close" viewBox="0 0 24 24">
            <path d="M6 6l12 12M18 6L6 18"></path>
        </symbol>
    </svg>

    <div class="app-shell">
        <aside
            class="sidebar"
            id="staffSidebar"
            aria-label="Staff navigation"
            tabindex="-1"
        >
            <div class="sidebar-heading">
                <a href="{{ route('dashboard') }}" class="brand">
                    <span class="brand-mark" aria-hidden="true">M</span>

                    <span>
                        <span class="brand-name">McLovin Resort</span>
                        <span class="brand-subtitle">Management workspace</span>
                    </span>
                </a>

                <button
                    type="button"
                    class="sidebar-close"
                    id="closeSidebar"
                    aria-label="Close navigation"
                >
                    <svg class="icon" aria-hidden="true">
                        <use href="#icon-close"></use>
                    </svg>
                </button>
            </div>

            <nav class="sidebar-nav" aria-label="Staff pages">
                @php
                    $previousGroup = null;
                @endphp

                @foreach($navigation as $item)
                    @if(\Illuminate\Support\Facades\Route::has($item['route']))
                        @if($previousGroup !== $item['group'])
                            <div class="nav-title">{{ $item['group'] }}</div>

                            @php
                                $previousGroup = $item['group'];
                            @endphp
                        @endif

                        <a
                            href="{{ route($item['route']) }}"
                            class="sidebar-link {{ request()->routeIs($item['pattern']) ? 'active' : '' }}"
                            @if(request()->routeIs($item['pattern'])) aria-current="page" @endif
                        >
                            <svg class="icon" aria-hidden="true">
                                <use href="#icon-{{ $item['icon'] }}"></use>
                            </svg>

                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endif
                @endforeach
            </nav>

            <div class="sidebar-footer">
                <a
                    href="{{ route('public.book') }}"
                    class="sidebar-link website-link"
                >
                    <svg class="icon" aria-hidden="true">
                        <use href="#icon-calendar"></use>
                    </svg>

                    <span>Public Booking</span>

                    <svg class="icon external-icon" aria-hidden="true">
                        <use href="#icon-external"></use>
                    </svg>
                </a>

                @auth
                    <div class="profile-box">
                        <div class="profile-row">
                            <span class="avatar" aria-hidden="true">
                                {{ $initial }}
                            </span>

                            <div class="profile-details">
                                <span class="profile-name">{{ $staff->name }}</span>
                                <span class="profile-email">{{ $staff->email }}</span>
                            </div>
                        </div>

                        <span class="role-badge">{{ $staff->role }}</span>
                    </div>

                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                        class="logout-form"
                    >
                        @csrf

                        <button type="submit" class="logout-btn">
                            <svg class="icon" aria-hidden="true">
                                <use href="#icon-logout"></use>
                            </svg>

                            Sign out
                        </button>
                    </form>
                @endauth
            </div>
        </aside>

        <button
            type="button"
            class="sidebar-backdrop"
            id="sidebarBackdrop"
            aria-label="Close navigation"
            tabindex="-1"
            hidden
        ></button>

        <div class="main" id="mainArea">
            <header class="topbar">
                <div class="topbar-leading">
                    <button
                        type="button"
                        class="menu-button"
                        id="openSidebar"
                        aria-label="Open navigation"
                        aria-controls="staffSidebar"
                        aria-expanded="false"
                    >
                        <svg class="icon" aria-hidden="true">
                            <use href="#icon-menu"></use>
                        </svg>
                    </button>

                    <div>
                        <p class="breadcrumb">McLovin Resort / Staff workspace</p>
                        <h1>{{ $pageTitle }}</h1>
                    </div>
                </div>

                <div class="topbar-meta">
                    <time
                        class="topbar-date"
                        datetime="{{ now()->format('Y-m-d') }}"
                    >
                        {{ now()->format('D, d M Y') }}
                    </time>

                    @auth
                        <span class="topbar-role">{{ $staff->role }}</span>
                    @endauth
                </div>
            </header>

            <main class="page-content" id="pageContent" tabindex="-1">
                {{ $slot }}
            </main>

            <footer class="app-footer">
                <span>© {{ date('Y') }} McLovin Resort</span>
                <span>Staff management workspace</span>
            </footer>
        </div>
    </div>

    <script>
        (() => {
            const sidebar = document.getElementById('staffSidebar');
            const openButton = document.getElementById('openSidebar');
            const closeButton = document.getElementById('closeSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const mainArea = document.getElementById('mainArea');
            const mobileQuery = window.matchMedia('(max-width: 1024px)');

            let previouslyFocused = null;

            function focusableElements() {
                return Array.from(sidebar.querySelectorAll(
                    'a[href], button:not([disabled]), input:not([disabled]), ' +
                    'select:not([disabled]), textarea:not([disabled]), ' +
                    '[tabindex]:not([tabindex="-1"])'
                )).filter((element) => {
                    return element.getClientRects().length > 0;
                });
            }

            function openNavigation() {
                if (!mobileQuery.matches) {
                    return;
                }

                previouslyFocused = document.activeElement;

                sidebar.inert = false;
                sidebar.removeAttribute('aria-hidden');
                sidebar.setAttribute('role', 'dialog');
                sidebar.setAttribute('aria-modal', 'true');

                mainArea.inert = true;
                backdrop.hidden = false;

                document.body.classList.add('sidebar-open');
                openButton.setAttribute('aria-expanded', 'true');

                requestAnimationFrame(() => {
                    closeButton.focus();
                });
            }

            function closeNavigation(restoreFocus = true) {
                const wasOpen = document.body.classList.contains('sidebar-open');

                document.body.classList.remove('sidebar-open');
                backdrop.hidden = true;
                mainArea.inert = false;

                openButton.setAttribute('aria-expanded', 'false');
                sidebar.removeAttribute('role');
                sidebar.removeAttribute('aria-modal');

                if (mobileQuery.matches) {
                    if (sidebar.contains(document.activeElement)) {
                        openButton.focus();
                    }

                    sidebar.inert = true;
                    sidebar.setAttribute('aria-hidden', 'true');
                } else {
                    sidebar.inert = false;
                    sidebar.removeAttribute('aria-hidden');
                }

                if (
                    restoreFocus &&
                    wasOpen &&
                    previouslyFocused instanceof HTMLElement &&
                    previouslyFocused.isConnected &&
                    previouslyFocused.getClientRects().length > 0
                ) {
                    previouslyFocused.focus();
                }
            }

            openButton.addEventListener('click', openNavigation);

            closeButton.addEventListener('click', () => {
                closeNavigation();
            });

            backdrop.addEventListener('click', () => {
                closeNavigation();
            });

            document.addEventListener('keydown', (event) => {
                if (
                    !mobileQuery.matches ||
                    !document.body.classList.contains('sidebar-open')
                ) {
                    return;
                }

                if (event.key === 'Escape') {
                    event.preventDefault();
                    closeNavigation();
                    return;
                }

                if (event.key !== 'Tab') {
                    return;
                }

                const elements = focusableElements();

                if (elements.length === 0) {
                    event.preventDefault();
                    sidebar.focus();
                    return;
                }

                const first = elements[0];
                const last = elements[elements.length - 1];

                if (event.shiftKey && document.activeElement === first) {
                    event.preventDefault();
                    last.focus();
                } else if (
                    !event.shiftKey &&
                    document.activeElement === last
                ) {
                    event.preventDefault();
                    first.focus();
                }
            });

            sidebar.querySelectorAll('a[href]').forEach((link) => {
                link.addEventListener('click', () => {
                    if (mobileQuery.matches) {
                        closeNavigation(false);
                    }
                });
            });

            function handleViewportChange() {
                closeNavigation(false);
            }

            if (typeof mobileQuery.addEventListener === 'function') {
                mobileQuery.addEventListener('change', handleViewportChange);
            } else {
                mobileQuery.addListener(handleViewportChange);
            }

            document.querySelectorAll('#pageContent table').forEach((table) => {
                if (table.closest('.table-responsive, .app-table-scroll')) {
                    return;
                }

                const wrapper = document.createElement('div');

                wrapper.className = 'app-table-scroll';
                wrapper.tabIndex = 0;
                wrapper.setAttribute('role', 'region');
                wrapper.setAttribute('aria-label', 'Scrollable data table');

                table.parentNode.insertBefore(wrapper, table);
                wrapper.appendChild(table);
            });

            window.addEventListener('pageshow', () => {
                closeNavigation(false);
            });

            handleViewportChange();
        })();
    </script>
</body>
</html>