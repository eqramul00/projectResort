<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Stay | McLovin Resort</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        * { box-sizing: border-box; }

        :root {
            --bg: #0d0d0f;
            --bg-2: #17181c;
            --panel: #121317;
            --panel-2: #1a1c21;
            --white: #ffffff;
            --silver: #d7dbe2;
            --silver-2: #a6adb8;
            --gold: #cfae47;
            --gold-2: #f2de97;
            --gold-dark: #9f7c1f;
            --text: #f5f5f7;
            --muted: #b8bfca;
            --line: rgba(255,255,255,.10);
            --line-gold: rgba(207,174,71,.30);
            --shadow: 0 16px 40px rgba(0, 0, 0, 0.35);
            --radius: 26px;
            --max: 1280px;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(207,174,71,.08), transparent 26%),
                radial-gradient(circle at top right, rgba(255,255,255,.04), transparent 22%),
                linear-gradient(180deg, var(--bg) 0%, var(--bg-2) 100%);
        }

        a { text-decoration: none; color: inherit; }
        img { max-width: 100%; display: block; }

        .container {
            width: min(var(--max), calc(100% - 32px));
            margin: 0 auto;
        }

        .hero {
            padding: 28px 0 110px;
            background:
                linear-gradient(rgba(0,0,0,.30), rgba(0,0,0,.62)),
                radial-gradient(circle at top right, rgba(242,222,151,0.12), transparent 26%),
                linear-gradient(135deg, #111214 0%, #1d2026 30%, #343942 55%, #15171c 75%, #b08b2f 100%);
            border-bottom: 1px solid rgba(255,255,255,.06);
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            padding-bottom: 28px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-link {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-mark {
            width: 52px;
            height: 52px;
            border-radius: 18px;
            overflow: hidden;
            background:
                linear-gradient(180deg, rgba(255,255,255,0.30) 0%, rgba(255,255,255,0.04) 30%, rgba(0,0,0,0.18) 100%),
                linear-gradient(135deg, #fff7db 0%, #cfae47 22%, #f4e3a4 40%, #8f6b18 65%, #f7ebc2 85%, #9f7c1f 100%);
            color: #161616;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 19px;
            border: 1px solid rgba(255,255,255,0.55);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.65),
                inset 0 -3px 6px rgba(90,70,20,0.18),
                0 12px 22px rgba(0,0,0,0.25);
        }

        .brand-mark img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .brand h2 {
            margin: 0;
            font-size: 20px;
        }

        .brand p {
            margin: 4px 0 0;
            font-size: 12px;
            color: rgba(255,255,255,0.75);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .nav-links {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: var(--silver);
            padding: 12px 18px;
            border-radius: 999px;
            font-weight: 700;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            transition: .25s ease;
        }

        .nav-links a:hover {
            background: rgba(255,255,255,0.10);
            color: var(--white);
        }

        .nav-links a.gold {
            background:
                linear-gradient(180deg, rgba(255,255,255,0.34) 0%, rgba(255,255,255,0.06) 24%, rgba(0,0,0,0.12) 100%),
                linear-gradient(135deg, #fff9e6 0%, #d4b14f 20%, #f6e6aa 38%, #aa8422 58%, #f9efca 78%, #8f6b18 100%);
            color: #181818;
            border: 1px solid rgba(255,255,255,0.60);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.72),
                inset 0 -3px 6px rgba(90,70,20,0.18),
                0 14px 28px rgba(0,0,0,0.22);
        }

        .hero-head {
            max-width: 860px;
            padding-top: 12px;
        }

        .hero-head span {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 999px;
            background: rgba(0,0,0,0.24);
            color: var(--gold-2);
            border: 1px solid var(--line-gold);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .hero-head h1 {
            margin: 18px 0 14px;
            font-size: 58px;
            line-height: 1.04;
            letter-spacing: -1.4px;
        }

        .hero-head p {
            color: rgba(255,255,255,0.82);
            font-size: 18px;
            line-height: 1.8;
            margin: 0;
            max-width: 760px;
        }

        .content-wrap {
            width: min(var(--max), calc(100% - 32px));
            margin: -72px auto 60px;
        }

        .card {
            background:
                linear-gradient(180deg, rgba(255,255,255,.05), rgba(255,255,255,.02)),
                linear-gradient(180deg, #16181d, #101116);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .card-body {
            padding: 28px;
        }

        .card h2 {
            margin: 0 0 10px;
            font-size: 32px;
        }

        .muted {
            color: var(--muted);
            line-height: 1.8;
        }

        .top-layout {
            display: grid;
            grid-template-columns: 1.05fr .95fr;
            gap: 26px;
            align-items: start;
        }

        .alert-success,
        .alert-error,
        .alert-info,
        .alert-warning {
            padding: 14px 16px;
            border-radius: 16px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.10);
            border: 1px solid rgba(34, 197, 94, 0.30);
            color: #86efac;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.10);
            border: 1px solid rgba(239, 68, 68, 0.30);
            color: #fca5a5;
        }

        .alert-info {
            background: rgba(59, 130, 246, 0.10);
            border: 1px solid rgba(59, 130, 246, 0.30);
            color: #93c5fd;
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.10);
            border: 1px solid rgba(245, 158, 11, 0.30);
            color: #fcd34d;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .full {
            grid-column: 1 / -1;
        }

        label {
            font-size: 14px;
            font-weight: 800;
            color: var(--silver);
        }

        input {
            width: 100%;
            border: 1px solid rgba(255,255,255,.10);
            border-radius: 16px;
            padding: 14px 16px;
            font-size: 14px;
            outline: none;
            background: #000000;
            color: var(--white);
            min-height: 52px;
        }

        input::placeholder {
            color: rgba(255,255,255,.55);
        }

        input:hover {
            border-color: rgba(207,174,71,.35);
        }

        input:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 4px rgba(207,174,71,.15);
        }

        .field-error {
            color: #f87171;
            font-size: 13px;
        }

        .btn {
            display: inline-block;
            border: none;
            cursor: pointer;
            border-radius: 16px;
            padding: 15px 22px;
            font-weight: 800;
            transition: 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn:disabled {
            opacity: .65;
            cursor: not-allowed;
            transform: none;
        }

        .btn-gold {
            background:
                linear-gradient(180deg, rgba(255,255,255,0.34) 0%, rgba(255,255,255,0.06) 24%, rgba(0,0,0,0.12) 100%),
                linear-gradient(135deg, #fff9e6 0%, #d4b14f 20%, #f6e6aa 38%, #aa8422 58%, #f9efca 78%, #8f6b18 100%);
            color: #181818;
            border: 1px solid rgba(255,255,255,0.60);
            box-shadow:
                inset 0 1px 0 rgba(255,255,255,0.72),
                inset 0 -3px 6px rgba(90,70,20,0.18),
                0 14px 28px rgba(0,0,0,0.22);
        }

        .date-input-wrap {
            position: relative;
        }

        .date-input-wrap input {
            padding-left: 46px;
            min-height: 52px;
            font-weight: 600;
            cursor: pointer;
        }

        .date-input-wrap input[readonly] {
            caret-color: transparent;
        }

        .date-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            pointer-events: none;
            opacity: .85;
            z-index: 2;
        }

        .date-help {
            color: var(--muted);
            font-size: 12px;
            margin-top: 2px;
        }

        .stay-summary {
            border: 1px solid rgba(255,255,255,.08);
            background: linear-gradient(135deg, #1a1c22, #13151a);
            border-radius: 18px;
            padding: 18px 20px;
        }

        .stay-summary-title {
            font-size: 14px;
            font-weight: 800;
            color: var(--gold-2);
            margin-bottom: 6px;
        }

        .stay-summary-text {
            color: var(--silver);
            line-height: 1.7;
            font-size: 14px;
        }

        .selected-preview {
            height: 100%;
        }

        .selected-main-image {
            height: 300px;
            background: linear-gradient(135deg, #22262d, #111318);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--silver-2);
            font-weight: 700;
        }

        .selected-main-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .selected-content {
            padding: 22px;
        }

        .selected-content h3 {
            margin: 0 0 8px;
            font-size: 30px;
        }

        .selected-meta {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin: 12px 0 16px;
        }

        .chip {
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            background: linear-gradient(180deg, #23262d 0%, #181b21 100%);
            border: 1px solid rgba(255,255,255,.08);
            color: var(--silver);
        }

        .selected-gallery {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-top: 16px;
        }

        .thumb {
            height: 82px;
            border-radius: 14px;
            overflow: hidden;
            border: 2px solid transparent;
            cursor: pointer;
            background: #1a1d23;
        }

        .thumb.active {
            border-color: var(--gold);
        }

        .thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .rooms-section {
            margin-top: 28px;
        }

        .rooms-head {
            display: flex;
            justify-content: space-between;
            align-items: end;
            gap: 16px;
            margin-bottom: 18px;
        }

        .rooms-head h2 {
            margin: 0;
            font-size: 32px;
        }

        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 22px;
        }

        .room-card {
            background:
                linear-gradient(180deg, rgba(255,255,255,.05), rgba(255,255,255,.02)),
                linear-gradient(180deg, #16181d, #101116);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .room-image {
            height: 240px;
            background: linear-gradient(135deg, #22262d, #111318);
        }

        .room-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .room-content {
            padding: 22px;
        }

        .room-content-top {
            display: flex;
            justify-content: space-between;
            align-items: start;
            gap: 16px;
            margin-bottom: 12px;
        }

        .room-content h3 {
            margin: 0;
            font-size: 26px;
        }

        .room-number {
            color: var(--muted);
            font-size: 14px;
            margin-top: 6px;
        }

        .price-box {
            background:
                linear-gradient(180deg, rgba(255,255,255,0.22) 0%, rgba(255,255,255,0.05) 25%, rgba(0,0,0,0.18) 100%),
                linear-gradient(135deg, #f8fbff 0%, #cfd5dd 18%, #edf2f7 38%, #9099a4 58%, #f8fbff 78%, #7f8893 100%);
            color: #1a1d22;
            border: 1px solid rgba(255,255,255,0.35);
            border-radius: 16px;
            padding: 10px 14px;
            font-weight: 800;
            white-space: nowrap;
        }

        .chips {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin: 12px 0 14px;
        }

        .room-note {
            color: var(--muted);
            line-height: 1.8;
        }

        .room-action {
            margin-top: 18px;
        }

        .empty-state {
            background:
                linear-gradient(180deg, rgba(255,255,255,.05), rgba(255,255,255,.02)),
                linear-gradient(180deg, #16181d, #101116);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 24px;
            padding: 26px;
            color: var(--muted);
            box-shadow: var(--shadow);
        }

        .custom-select {
            position: relative;
        }

        .select-selected {
            background: #000000;
            border: 1px solid rgba(255,255,255,.12);
            padding: 14px 46px 14px 16px;
            border-radius: 16px;
            cursor: pointer;
            color: #ffffff;
            position: relative;
            min-height: 52px;
            display: flex;
            align-items: center;
            transition: .2s ease;
        }

        .select-selected:hover {
            border-color: rgba(207,174,71,.45);
        }

        .select-selected.active {
            border-color: var(--gold);
            box-shadow: 0 0 0 4px rgba(207,174,71,.15);
        }

        .select-selected::after {
            content: "▼";
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gold);
            font-size: 12px;
            pointer-events: none;
        }

        .select-items {
            position: absolute;
            top: calc(100% + 10px);
            left: 0;
            right: 0;
            z-index: 99;
            background: #000000;
            border: 1px solid rgba(255,255,255,.10);
            border-radius: 18px;
            overflow: hidden;
            max-height: 280px;
            overflow-y: auto;
            box-shadow: 0 18px 36px rgba(0,0,0,.45);
        }

        .select-hide {
            display: none;
        }

        .select-option {
            padding: 14px 16px;
            color: #dddddd;
            cursor: pointer;
            border-bottom: 1px solid rgba(255,255,255,.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            transition: .2s ease;
        }

        .select-option:last-child {
            border-bottom: none;
        }

        .select-option:hover {
            background: rgba(207,174,71,.12);
            color: #ffffff;
        }

        .select-option strong {
            font-size: 14px;
            font-weight: 700;
        }

        .select-option span {
            color: var(--gold);
            font-weight: 800;
            white-space: nowrap;
            font-size: 13px;
        }

        .flatpickr-calendar {
            background: #111318;
            border: 1px solid rgba(255,255,255,.10);
            box-shadow: 0 18px 36px rgba(0,0,0,.45);
        }

        .flatpickr-months .flatpickr-month,
        .flatpickr-current-month .flatpickr-monthDropdown-months,
        .flatpickr-current-month input.cur-year {
            color: #ffffff;
            fill: #ffffff;
        }

        .flatpickr-weekday {
            color: var(--gold-2);
        }

        .flatpickr-day {
            color: #e5e7eb;
        }

        .flatpickr-day.today {
            border-color: var(--gold);
        }

        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange {
            background: var(--gold);
            border-color: var(--gold);
            color: #111111;
        }

        .flatpickr-day:hover {
            background: rgba(207,174,71,.20);
            border-color: rgba(207,174,71,.35);
        }

        @media (max-width: 1100px) {
            .top-layout {
                grid-template-columns: 1fr;
            }

            .rooms-grid {
                grid-template-columns: 1fr;
            }

            .hero-head h1 {
                font-size: 44px;
            }

            .selected-gallery {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 700px) {
            .container,
            .content-wrap {
                width: calc(100% - 20px);
            }

            .nav {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav-links {
                width: 100%;
            }

            .nav-links a {
                width: 100%;
                text-align: center;
            }

            .hero {
                padding-bottom: 90px;
            }

            .hero-head h1 {
                font-size: 34px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .content-wrap {
                margin-top: -48px;
            }

            .selected-gallery {
                grid-template-columns: repeat(2, 1fr);
            }

            .room-content-top,
            .rooms-head {
                flex-direction: column;
                align-items: flex-start;
            }

            .selected-main-image,
            .room-image {
                height: 220px;
            }

            .select-option {
                flex-direction: column;
                align-items: flex-start;
            }

            .select-option span {
                white-space: normal;
            }
        }
    </style>
</head>
<body>
    <section class="hero" id="top">
        <div class="container">
            <div class="nav">
                <a href="{{ route('welcome') }}" class="brand brand-link">
                    <div class="brand-mark">
                        <img src="/images/logo.jpg" alt="McLovin Resort Logo">
                    </div>
                    <div>
                        <h2>McLovin Resort</h2>
                        <p>Premium Direct Reservation</p>
                    </div>
                </a>

                <div class="nav-links">
                    <a href="{{ route('welcome') }}">Home</a>
                    <a href="{{ route('login') }}">Staff Login</a>
                    <a href="{{ route('public.book') }}" class="gold">Book Now</a>
                </div>
            </div>

            <div class="hero-head">
                <span>Premium Reservation Desk</span>
                <h1>Choose your room and continue to payment</h1>
                <p>
                    Explore available rooms, choose your stay dates, complete your details, and continue to the payment page after booking.
                </p>
            </div>
        </div>
    </section>

    <div class="content-wrap">
        <div class="top-layout">
            <div class="card">
                <div class="card-body">
                    <h2>Book Your Stay</h2>
                    <p class="muted">Choose your room, enter your details, then continue to payment.</p>

                    @if(session('success'))
                        <div class="alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert-error">{{ session('error') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert-error">
                            <ul style="margin:0; padding-left:18px;">
                                @foreach($errors->all() as $error)
                                    <li style="margin-bottom:6px;">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('public.book.store') }}" id="bookingForm">
                        @csrf

                        <div class="form-grid">
                            <div class="form-group full">
                                <label>Select Room</label>

                                <input type="hidden" name="room_id" id="roomInput" value="{{ old('room_id') }}">

                                <div class="custom-select" id="customSelect">
                                    <div class="select-selected" id="selectSelected">Choose a room</div>

                                    <div class="select-items select-hide" id="selectItems">
                                        @foreach($rooms as $room)
                                            <div
                                                class="select-option"
                                                data-id="{{ $room->id }}"
                                                data-name="{{ $room->name }}"
                                                data-number="{{ $room->number }}"
                                                data-type="{{ $room->type ?: 'Premium Room' }}"
                                                data-status="Visible for booking"
                                                data-price="৳ {{ number_format($room->price, 0) }}"
                                                data-description="A premium resort room crafted for comfort, privacy, and a refined stay experience."
                                                data-images="@foreach($room->images as $img)/{{ $img->image_path }}@if(!$loop->last)|@endif @endforeach"
                                                data-bookings="@foreach($room->bookings->where('status', '!=', 'cancelled') as $booking){{ $booking->check_in }}|{{ $booking->check_out }}@if(!$loop->last);@endif @endforeach"
                                            >
                                                <strong>Room {{ $room->number }} — {{ $room->name }}</strong>
                                                <span>৳ {{ number_format($room->price, 0) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                @error('room_id') <div class="field-error">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter your full name">
                                @error('name') <div class="field-error">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Enter your phone number">
                                @error('phone') <div class="field-error">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group full">
                                <label>Email Address</label>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email address">
                                @error('email') <div class="field-error">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label for="checkIn">Check In</label>
                                <div class="date-input-wrap">
                                    <span class="date-icon">📅</span>
                                    <input
                                        id="checkIn"
                                        type="text"
                                        name="check_in"
                                        value="{{ old('check_in') }}"
                                        placeholder="Select check-in date"
                                        readonly
                                    >
                                </div>
                                <small class="date-help">Choose your arrival date from the calendar</small>
                                @error('check_in') <div class="field-error">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group">
                                <label for="checkOut">Check Out</label>
                                <div class="date-input-wrap">
                                    <span class="date-icon">📅</span>
                                    <input
                                        id="checkOut"
                                        type="text"
                                        name="check_out"
                                        value="{{ old('check_out') }}"
                                        placeholder="Select check-out date"
                                        readonly
                                    >
                                </div>
                                <small class="date-help">Choose your departure date from the calendar</small>
                                @error('check_out') <div class="field-error">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-group full">
                                <div class="stay-summary" id="staySummary">
                                    <div class="stay-summary-title">Stay Summary</div>
                                    <div class="stay-summary-text" id="staySummaryText">
                                        Please select your check-in and check-out dates.
                                    </div>
                                </div>
                            </div>

                            <div class="form-group full" id="roomConflictBox" style="display:none;">
                                <div class="alert-warning" id="roomConflictText">
                                    This room is already booked for the selected dates. Please choose another room or try different dates.
                                </div>
                            </div>

                            <div class="form-group full">
                                <button type="submit" class="btn btn-gold" style="width:100%;" id="submitBookingBtn">
                                    Continue to Payment
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card selected-preview" id="selectedPreview">
                <div class="selected-main-image" id="selectedMainImage">
                    Select a room to preview it here
                </div>

                <div class="selected-content">
                    <h3 id="selectedName">No room selected</h3>
                    <div class="muted" id="selectedNumber">Choose a room from the list</div>

                    <div class="selected-meta">
                        <span class="chip" id="selectedType">Type</span>
                        <span class="chip" id="selectedStatus">Booking View</span>
                        <span class="chip" id="selectedPrice">Price</span>
                    </div>

                    <div class="muted" id="selectedDescription">
                        Your selected room details and photo gallery will appear here.
                    </div>

                    <div class="selected-gallery" id="selectedGallery"></div>
                </div>
            </div>
        </div>

        <div class="rooms-section">
            <div class="rooms-head">
                <div>
                    <h2>Rooms</h2>
                    <p class="muted" style="margin:6px 0 0;">All available rooms are shown here.</p>
                </div>
            </div>

            @if($rooms->count())
                <div class="rooms-grid">
                    @foreach($rooms as $room)
                        <div class="room-card">
                            <div class="room-image">
                                @if($room->images->first())
                                    <img src="/{{ $room->images->first()->image_path }}" alt="{{ $room->name }}">
                                @else
                                    <div style="height:100%;display:flex;align-items:center;justify-content:center;color:#94a3b8;font-weight:700;">
                                        No Room Image
                                    </div>
                                @endif
                            </div>

                            <div class="room-content">
                                <div class="room-content-top">
                                    <div>
                                        <h3>{{ $room->name }}</h3>
                                        <div class="room-number">Room {{ $room->number }}</div>
                                    </div>

                                    <div class="price-box">
                                        ৳ {{ number_format($room->price, 0) }}
                                    </div>
                                </div>

                                <div class="chips">
                                    <span class="chip">{{ $room->type ?: 'Premium Room' }}</span>
                                    <span class="chip">Select dates to check</span>
                                    <span class="chip">{{ $room->images->count() }} Photo{{ $room->images->count() == 1 ? '' : 's' }}</span>
                                </div>

                                <div class="room-note">
                                    A refined resort stay with premium comfort, elegant presentation, and a peaceful hill-retreat atmosphere.
                                </div>

                                <div class="room-action">
                                    <button
                                        type="button"
                                        class="btn btn-gold choose-room-btn"
                                        data-room-id="{{ $room->id }}"
                                    >
                                        Choose This Room
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    No rooms are available right now.
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        const roomInput = document.getElementById('roomInput');
        const selectSelected = document.getElementById('selectSelected');
        const selectItems = document.getElementById('selectItems');
        const selectOptions = document.querySelectorAll('.select-option');

        const selectedMainImage = document.getElementById('selectedMainImage');
        const selectedName = document.getElementById('selectedName');
        const selectedNumber = document.getElementById('selectedNumber');
        const selectedType = document.getElementById('selectedType');
        const selectedStatus = document.getElementById('selectedStatus');
        const selectedPrice = document.getElementById('selectedPrice');
        const selectedDescription = document.getElementById('selectedDescription');
        const selectedGallery = document.getElementById('selectedGallery');
        const chooseButtons = document.querySelectorAll('.choose-room-btn');

        const checkInInput = document.getElementById('checkIn');
        const checkOutInput = document.getElementById('checkOut');
        const staySummaryText = document.getElementById('staySummaryText');
        const roomConflictBox = document.getElementById('roomConflictBox');
        const submitBookingBtn = document.getElementById('submitBookingBtn');

        let checkInPicker;
        let checkOutPicker;

        function resetPreview() {
            selectedMainImage.innerHTML = 'Select a room to preview it here';
            selectedName.textContent = 'No room selected';
            selectedNumber.textContent = 'Choose a room from the list';
            selectedType.textContent = 'Type';
            selectedStatus.textContent = 'Booking View';
            selectedPrice.textContent = 'Price';
            selectedDescription.textContent = 'Your selected room details and photo gallery will appear here.';
            selectedGallery.innerHTML = '';
        }

        function parseBookings(bookingsString) {
            if (!bookingsString || bookingsString.trim() === '') {
                return [];
            }

            return bookingsString
                .split(';')
                .map(item => item.trim())
                .filter(item => item !== '')
                .map(item => {
                    const parts = item.split('|');
                    return {
                        check_in: parts[0] ? parts[0].trim() : '',
                        check_out: parts[1] ? parts[1].trim() : ''
                    };
                })
                .filter(item => item.check_in && item.check_out);
        }

        function hasDateConflict(bookings, checkIn, checkOut) {
            return bookings.some(booking => {
                return booking.check_in < checkOut && booking.check_out > checkIn;
            });
        }

        function renderRoomFromData(data) {
            if (!data || !data.id) {
                resetPreview();
                return;
            }

            const name = data.name || 'No room selected';
            const number = data.number || '';
            const type = data.type || 'Premium Room';
            const status = data.status || 'Visible for booking';
            const price = data.price || 'Price not available';
            const description = data.description || 'No room description available.';
            const images = data.images
                ? data.images.split('|').map(img => img.trim()).filter(img => img !== '')
                : [];

            selectedName.textContent = name;
            selectedNumber.textContent = number ? 'Room ' + number : 'Choose a room from the list';
            selectedType.textContent = type;
            selectedStatus.textContent = status;
            selectedPrice.textContent = price;
            selectedDescription.textContent = description;

            if (images.length > 0) {
                selectedMainImage.innerHTML = `<img src="${images[0]}" alt="${name}">`;
            } else {
                selectedMainImage.innerHTML = 'No room image available';
            }

            selectedGallery.innerHTML = '';

            images.forEach((img, index) => {
                const thumb = document.createElement('div');
                thumb.className = 'thumb' + (index === 0 ? ' active' : '');

                const imageEl = document.createElement('img');
                imageEl.src = img;
                imageEl.alt = name;

                thumb.appendChild(imageEl);

                thumb.addEventListener('click', function () {
                    selectedMainImage.innerHTML = `<img src="${img}" alt="${name}">`;

                    document.querySelectorAll('#selectedGallery .thumb').forEach(t => {
                        t.classList.remove('active');
                    });

                    thumb.classList.add('active');
                });

                selectedGallery.appendChild(thumb);
            });
        }

        function updateSelectedText(optionEl) {
            selectSelected.textContent = optionEl.querySelector('strong')?.textContent || 'Choose a room';
        }

        function showConflictMessage() {
            roomConflictBox.style.display = 'block';
            submitBookingBtn.disabled = true;
        }

        function hideConflictMessage() {
            roomConflictBox.style.display = 'none';
            submitBookingBtn.disabled = false;
        }

        function validateCurrentSelection() {
            const selectedOption = document.querySelector('.select-option.selected');
            const checkIn = checkInInput.value;
            const checkOut = checkOutInput.value;

            if (!selectedOption || !checkIn || !checkOut) {
                hideConflictMessage();
                return;
            }

            if (checkOut <= checkIn) {
                hideConflictMessage();
                return;
            }

            const bookings = parseBookings(selectedOption.dataset.bookings || '');

            if (hasDateConflict(bookings, checkIn, checkOut)) {
                showConflictMessage();
            } else {
                hideConflictMessage();
            }
        }

        function setSelectedRoom(optionEl, scrollToTop = false) {
            const data = {
                id: optionEl.dataset.id,
                name: optionEl.dataset.name,
                number: optionEl.dataset.number,
                type: optionEl.dataset.type,
                status: optionEl.dataset.status,
                price: optionEl.dataset.price,
                description: optionEl.dataset.description,
                images: optionEl.dataset.images
            };

            roomInput.value = data.id;
            updateSelectedText(optionEl);
            renderRoomFromData(data);

            document.querySelectorAll('.select-option').forEach(item => item.classList.remove('selected'));
            optionEl.classList.add('selected');

            if (scrollToTop) {
                document.querySelector('.content-wrap').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }

            validateCurrentSelection();
        }

        function formatReadableDate(dateString) {
            if (!dateString) return '';

            const date = new Date(dateString + 'T00:00:00');
            return date.toLocaleDateString('en-GB', {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            });
        }

        function getTomorrow(dateString) {
            const date = new Date(dateString + 'T00:00:00');
            date.setDate(date.getDate() + 1);

            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');

            return `${year}-${month}-${day}`;
        }

        function calculateNights(checkIn, checkOut) {
            const start = new Date(checkIn + 'T00:00:00');
            const end = new Date(checkOut + 'T00:00:00');
            const diff = end - start;
            return Math.ceil(diff / (1000 * 60 * 60 * 24));
        }

        function updateStaySummary() {
            const checkIn = checkInInput.value;
            const checkOut = checkOutInput.value;

            if (!checkIn && !checkOut) {
                staySummaryText.textContent = 'Please select your check-in and check-out dates.';
                return;
            }

            if (checkIn && !checkOut) {
                staySummaryText.textContent = `Arrival selected for ${formatReadableDate(checkIn)}. Please choose your check-out date.`;
                return;
            }

            if (!checkIn && checkOut) {
                staySummaryText.textContent = `Departure selected for ${formatReadableDate(checkOut)}. Please choose your check-in date first.`;
                return;
            }

            const nights = calculateNights(checkIn, checkOut);

            if (nights <= 0) {
                staySummaryText.textContent = 'Check-out must be after check-in.';
                return;
            }

            staySummaryText.textContent =
                `Your stay is from ${formatReadableDate(checkIn)} to ${formatReadableDate(checkOut)} for ${nights} night${nights > 1 ? 's' : ''}.`;
        }

        function setupBookingCalendar() {
            checkInPicker = flatpickr("#checkIn", {
                dateFormat: "Y-m-d",
                minDate: "today",
                allowInput: false,
                clickOpens: true,
                disableMobile: true,
                defaultDate: checkInInput.value || null,
                onChange: function(selectedDates, dateStr) {
                    if (dateStr) {
                        const nextDay = new Date(selectedDates[0]);
                        nextDay.setDate(nextDay.getDate() + 1);

                        checkOutPicker.set("minDate", nextDay);

                        if (checkOutInput.value) {
                            const currentCheckOut = new Date(checkOutInput.value + 'T00:00:00');
                            if (currentCheckOut <= selectedDates[0]) {
                                checkOutPicker.setDate(nextDay, true);
                            }
                        }
                    } else {
                        checkOutPicker.set("minDate", "today");
                    }

                    updateStaySummary();
                    validateCurrentSelection();
                }
            });

            checkOutPicker = flatpickr("#checkOut", {
                dateFormat: "Y-m-d",
                minDate: checkInInput.value ? getTomorrow(checkInInput.value) : "today",
                allowInput: false,
                clickOpens: true,
                disableMobile: true,
                defaultDate: checkOutInput.value || null,
                onChange: function() {
                    updateStaySummary();
                    validateCurrentSelection();
                }
            });

            updateStaySummary();
        }

        selectSelected.addEventListener('click', function () {
            selectItems.classList.toggle('select-hide');
            selectSelected.classList.toggle('active');
        });

        selectOptions.forEach(option => {
            option.addEventListener('click', function () {
                setSelectedRoom(this, false);
                selectItems.classList.add('select-hide');
                selectSelected.classList.remove('active');
            });
        });

        document.addEventListener('click', function (e) {
            if (!e.target.closest('.custom-select')) {
                selectItems.classList.add('select-hide');
                selectSelected.classList.remove('active');
            }
        });

        chooseButtons.forEach(button => {
            button.addEventListener('click', function () {
                const roomId = this.dataset.roomId;
                const matchedOption = document.querySelector(`.select-option[data-id="${roomId}"]`);

                if (matchedOption) {
                    setSelectedRoom(matchedOption, true);
                }
            });
        });

        document.getElementById('bookingForm').addEventListener('submit', function (e) {
            validateCurrentSelection();

            if (submitBookingBtn.disabled) {
                e.preventDefault();
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const oldRoomId = roomInput.value;
            let initialOption = null;

            if (oldRoomId) {
                initialOption = document.querySelector(`.select-option[data-id="${oldRoomId}"]`);
            }

            if (!initialOption) {
                initialOption = document.querySelector('.select-option');
            }

            if (initialOption) {
                setSelectedRoom(initialOption, false);
            } else {
                resetPreview();
            }

            setupBookingCalendar();
            validateCurrentSelection();
        });
    </script>
</body>
</html>