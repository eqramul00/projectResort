@php
    $currentUser = auth()->user();
    $isGuest = $currentUser && $currentUser->isGuest();
    $guestProfile = $isGuest ? $currentUser->guest : null;

    $today = now()->toDateString();
    $tomorrow = now()->addDay()->toDateString();

    $rateMinor = (int) round((float) $selectedRoom->price * 100);
    $canSubmit = !$isGuest || $guestProfile !== null;

    $photos = [];

    foreach ($selectedRoom->images as $image) {
        if (!$image->image_path) {
            continue;
        }

        $path = ltrim($image->image_path, '/');

        $photos[] = str_starts_with($path, 'storage/')
            ? asset($path)
            : asset('storage/' . $path);
    }
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $selectedRoom->name }} | McLovin Resort</title>

    <style>
        :root {
            --forest: #173c32;
            --dark: #102b24;
            --cream: #f7f5ef;
            --ink: #233830;
            --muted: #627068;
            --line: #dde3dc;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            color: var(--ink);
            background: var(--cream);
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.6;
        }

        a { color: inherit; }
        img { display: block; max-width: 100%; }
        input, button { font: inherit; }

        .container {
            width: min(1160px, calc(100% - 48px));
            margin-inline: auto;
        }

        header {
            border-bottom: 1px solid var(--line);
        }

        .nav {
            min-height: 85px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand img {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            object-fit: contain;
            background: #ffffff;
        }

        .brand strong {
            display: block;
            font-family: Georgia, serif;
            font-weight: 400;
            font-size: 23px;
        }

        .brand small {
            display: block;
            color: var(--muted);
            font-size: 9px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .nav-links {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            font-size: 13px;
        }

        .nav-links a {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            text-decoration: none;
        }

        .intro {
            padding: 36px 0 25px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            min-height: 44px;
            margin-bottom: 14px;
            font-size: 13px;
            color: var(--muted);
            text-decoration: none;
        }

        .eyebrow {
            display: block;
            color: #66784d;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        h1 {
            margin: 0 0 12px;
            font-family: Georgia, serif;
            font-weight: 400;
            font-size: clamp(35px, 4.5vw, 54px);
            line-height: 1.1;
            overflow-wrap: anywhere;
        }

        .intro p {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
        }

        .gallery {
            margin-bottom: 32px;
        }

        .main-photo {
            position: relative;
            aspect-ratio: 21 / 9;
            max-height: 490px;
            background: #dce5d5;
            border-radius: 22px;
            overflow: hidden;
            display: grid;
            place-items: center;
            color: var(--muted);
        }

        .main-photo > img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-controls {
            position: absolute;
            left: 18px;
            right: 18px;
            bottom: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            pointer-events: none;
        }

        .photo-counter {
            padding: 7px 12px;
            color: #ffffff;
            background: rgba(16,43,36,.85);
            border-radius: 8px;
            font-size: 12px;
        }

        .gallery-buttons {
            display: flex;
            gap: 8px;
            pointer-events: auto;
        }

        .gallery-buttons button {
            width: 44px;
            height: 44px;
            border: 0;
            border-radius: 50%;
            background: #ffffff;
            color: var(--forest);
            cursor: pointer;
            font-size: 20px;
        }

        .thumbnails {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding: 14px 3px 5px;
        }

        .thumbnail {
            display: block;
            flex: 0 0 100px;
            width: 100px;
            height: 72px;
            overflow: hidden;
            border: 2px solid transparent;
            border-radius: 10px;
        }

        .thumbnail.is-active {
            border-color: var(--forest);
        }

        .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .layout {
            display: grid;
            grid-template-columns: minmax(0, 1.4fr) minmax(0, .85fr);
            gap: 28px;
            align-items: start;
            padding-bottom: 65px;
        }

        .card {
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: 20px;
            padding: 30px;
            min-width: 0;
        }

        .card h2 {
            margin: 0 0 22px;
            font-family: Georgia, serif;
            font-size: 28px;
            font-weight: 400;
            line-height: 1.2;
        }

        .section + .section {
            margin-top: 27px;
            padding-top: 25px;
            border-top: 1px solid var(--line);
        }

        .section h3 {
            margin: 0 0 17px;
            font-size: 15px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .field { min-width: 0; }
        .full { grid-column: 1 / -1; }

        label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            min-width: 0;
            min-height: 49px;
            padding: 12px;
            border: 1px solid #c9d0c6;
            border-radius: 9px;
            background: #ffffff;
            color: var(--ink);
            font-size: 15px;
        }

        .help {
            font-size: 12px;
            color: var(--muted);
            line-height: 1.8;
        }

        .help a {
            color: var(--forest);
            font-weight: 700;
        }

        .profile {
            background: #edf1e8;
            padding: 17px;
            border-radius: 12px;
            font-size: 14px;
            overflow-wrap: anywhere;
        }

        .profile p { margin: 0 0 5px; }

        .notice {
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 22px;
            font-size: 13px;
            line-height: 1.7;
        }

        .error {
            background: #fff0ec;
            color: #942e24;
            border: 1px solid #efc4b8;
        }

        .info {
            background: #edf2e7;
            color: #425b35;
            border: 1px solid #d0ddc4;
        }

        .submit {
            width: 100%;
            min-height: 52px;
            padding: 14px;
            margin-top: 22px;
            border: 0;
            border-radius: 10px;
            background: var(--forest);
            color: #ffffff;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
        }

        .submit:hover { background: var(--dark); }

        .submit:disabled {
            background: #d6ddd1;
            color: #61705c;
            cursor: not-allowed;
        }

        .summary {
            position: sticky;
            top: 24px;
        }

        .summary .eyebrow { margin-bottom: 10px; }

        .summary h2 {
            font-size: 29px;
            margin-bottom: 8px;
            overflow-wrap: anywhere;
        }

        .summary-type {
            margin: 0 0 24px;
            color: var(--muted);
            font-size: 12px;
        }

        dl { margin: 0; }

        .row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 16px;
            font-size: 13px;
        }

        dt { color: var(--muted); }
        dd { margin: 0; text-align: right; }

        .total {
            margin-top: 22px;
            padding-top: 20px;
            border-top: 1px solid var(--line);
            align-items: center;
        }

        .total strong {
            font-size: 27px;
            color: var(--forest);
        }

        .change-room {
            display: block;
            margin-top: 22px;
            color: var(--forest);
            text-align: center;
            font-size: 13px;
        }

        footer {
            padding: 25px;
            color: #d2ded4;
            background: var(--dark);
            text-align: center;
            font-size: 12px;
        }

        :focus-visible {
            outline: 3px solid #ac7624;
            outline-offset: 4px;
        }

        @media (max-width: 760px) {
            .layout { grid-template-columns: 1fr; }
            .summary { position: static; }
            .main-photo { aspect-ratio: 16 / 11; }
            .nav { flex-wrap: wrap; padding: 15px 0; gap: 10px; }
        }

        @media (max-width: 480px) {
            .container { width: calc(100% - 32px); }
            .card { padding: 24px 20px; }
            .grid { grid-template-columns: 1fr; }
            .brand strong { font-size: 21px; }
            .nav-links { width: 100%; }
            .thumbnail { flex-basis: 75px; width: 75px; height: 57px; }
            .main-photo { border-radius: 16px; }
            .photo-controls { left: 12px; right: 12px; bottom: 12px; }
        }
    </style>
</head>

<body>
    <header>
        <div class="container nav">
            <a href="{{ route('welcome') }}" class="brand">
                <img src="{{ asset('images/logo.jpg') }}" alt="" width="44" height="44">

                <span>
                    <strong>McLovin Resort</strong>
                    <small>A little closer to nature</small>
                </span>
            </a>

            <nav class="nav-links" aria-label="Navigation">
                <a href="{{ route('welcome') }}">Home</a>

                @if($isGuest)
                    <a href="{{ route('guest.dashboard') }}">My Bookings</a>
                @elseif(!$currentUser)
                    <a href="{{ route('guest.login') }}">Guest Login</a>
                @elseif($currentUser->canViewBookings())
                    <a href="{{ route('dashboard') }}">Staff Dashboard</a>
                @endif
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="intro">
            <a href="{{ route('public.book') }}" class="back-link">
                ← Explore other rooms
            </a>

            <span class="eyebrow">Step 2 of 3 · Dates & details</span>
            <h1>{{ $selectedRoom->name }}</h1>

            <p>
                Room {{ $selectedRoom->number }} ·
                {{ $selectedRoom->type ?: 'Guest room' }}
            </p>
        </div>

        <section class="gallery" aria-label="Selected room photographs">
            <div class="main-photo">
                @if(count($photos) > 0)
                    <img
                        src="{{ $photos[0] }}"
                        alt="{{ $selectedRoom->name }}"
                        id="mainPhoto"
                        width="1200"
                        height="515"
                        fetchpriority="high"
                    >

                    <div class="photo-controls">
                        <span class="photo-counter" id="photoCounter" aria-live="polite">
                            1 / {{ count($photos) }} photos
                        </span>

                        @if(count($photos) > 1)
                            <div class="gallery-buttons" id="galleryButtons" hidden>
                                <button type="button" id="previousPhoto" aria-label="Previous photo">
                                    ←
                                </button>

                                <button type="button" id="nextPhoto" aria-label="Next photo">
                                    →
                                </button>
                            </div>
                        @endif
                    </div>
                @else
                    Room photos coming soon
                @endif
            </div>

            @if(count($photos) > 1)
                <div class="thumbnails" aria-label="Choose a room photograph">
                    @foreach($photos as $photo)
                        <a
                            href="{{ $photo }}"
                            class="thumbnail {{ $loop->first ? 'is-active' : '' }}"
                            data-thumbnail
                            aria-label="View photo {{ $loop->iteration }} of {{ $selectedRoom->name }}"
                            @if($loop->first) aria-current="true" @endif
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <img
                                src="{{ $photo }}"
                                alt=""
                                width="100"
                                height="72"
                                loading="lazy"
                            >
                        </a>
                    @endforeach
                </div>
            @endif
        </section>

        <div class="layout">
            <section class="card" aria-labelledby="formHeading">
                <h2 id="formHeading">Make this stay yours.</h2>

                @if($errors->any())
                    <div class="notice error" role="alert">
                        <strong>Please check your booking details.</strong>

                        <ul style="padding-left:20px; margin-bottom:0;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('error'))
                    <div class="notice error" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('public.book.store') }}"
                    id="bookingForm"
                    data-rate-minor="{{ $rateMinor }}"
                >
                    @csrf

                    <input type="hidden" name="room_id" value="{{ $selectedRoom->id }}">

                    <div class="section">
                        <h3>Choose your dates</h3>

                        <div class="grid">
                            <div class="field">
                                <label for="checkIn">Check-in date</label>
                                <input
                                    type="date"
                                    id="checkIn"
                                    name="check_in"
                                    min="{{ $today }}"
                                    value="{{ old('check_in') }}"
                                    required
                                >
                            </div>

                            <div class="field">
                                <label for="checkOut">Check-out date</label>
                                <input
                                    type="date"
                                    id="checkOut"
                                    name="check_out"
                                    min="{{ $tomorrow }}"
                                    value="{{ old('check_out') }}"
                                    required
                                >
                            </div>
                        </div>

                        <p class="help">
                            Availability is checked when you submit.
                            This room is not reserved until your booking is saved.
                        </p>
                    </div>

                    <div class="section">
                        <h3>Guest details</h3>

                        @if($isGuest)
                            @if($guestProfile)
                                <div class="profile">
                                    <p><strong>{{ $guestProfile->full_name }}</strong></p>
                                    <p>{{ $guestProfile->email }}</p>
                                    <p>{{ $guestProfile->phone }}</p>
                                </div>

                                <p class="help">
                                    Your booking will appear in My Bookings.
                                </p>
                            @else
                                <div class="notice error">
                                    Your guest profile could not be found.
                                    Please contact reception.
                                </div>
                            @endif
                        @else
                            @if(!$currentUser)
                                <p class="help">
                                    <a href="{{ route('guest.login') }}">Log in before booking</a>
                                    to keep this reservation in your account,
                                    or continue without an account.
                                </p>
                            @endif

                            <div class="grid">
                                <div class="field full">
                                    <label for="guestName">Full name</label>
                                    <input
                                        type="text"
                                        id="guestName"
                                        name="name"
                                        maxlength="255"
                                        autocomplete="name"
                                        value="{{ old('name') }}"
                                        required
                                    >
                                </div>

                                <div class="field">
                                    <label for="guestEmail">Email address</label>
                                    <input
                                        type="email"
                                        id="guestEmail"
                                        name="email"
                                        maxlength="255"
                                        autocomplete="email"
                                        value="{{ old('email') }}"
                                        required
                                    >
                                </div>

                                <div class="field">
                                    <label for="guestPhone">Phone number</label>
                                    <input
                                        type="tel"
                                        id="guestPhone"
                                        name="phone"
                                        maxlength="50"
                                        autocomplete="tel"
                                        value="{{ old('phone') }}"
                                        required
                                    >
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="section">
                        <div class="notice info" style="margin-bottom:0;">
                            Your booking starts as pending.
                            Submit payment details on the next page.
                            Our team will review payment before confirming
                            your reservation.
                        </div>

                        <button
                            type="submit"
                            id="submitBooking"
                            class="submit"
                            @if(!$canSubmit) disabled @endif
                        >
                            Continue to payment →
                        </button>

                        <p class="help" style="text-align:center; margin-bottom:0;">
                            No card is charged by this button.
                        </p>
                    </div>
                </form>
            </section>

            <aside class="card summary" aria-labelledby="summaryHeading">
                <span class="eyebrow">Your selected stay</span>
                <h2 id="summaryHeading">{{ $selectedRoom->name }}</h2>

                <p class="summary-type">
                    {{ $selectedRoom->type ?: 'Guest room' }} ·
                    Room {{ $selectedRoom->number }}
                </p>

                <dl>
                    <div class="row">
                        <dt>Check-in</dt>
                        <dd id="summaryCheckIn">Choose a date</dd>
                    </div>

                    <div class="row">
                        <dt>Check-out</dt>
                        <dd id="summaryCheckOut">Choose a date</dd>
                    </div>

                    <div class="row">
                        <dt>Length of stay</dt>
                        <dd id="summaryNights">—</dd>
                    </div>

                    <div class="row">
                        <dt>Nightly rate</dt>
                        <dd>৳ {{ number_format($rateMinor / 100, 2) }}</dd>
                    </div>

                    <div class="row total">
                        <dt>Estimated room total</dt>
                        <dd>
                            <strong id="summaryTotal" aria-live="polite">—</strong>
                        </dd>
                    </div>
                </dl>

                <p class="help">
                    Estimate based on the displayed nightly rate.
                    The current agreed rate is saved when your booking
                    is created. Review the saved total on the payment page.
                </p>

                <a href="{{ route('public.book') }}" class="change-room">
                    Choose a different room
                </a>
            </aside>
        </div>
    </main>

    <footer>
        © {{ date('Y') }} McLovin Resort ·
        <a href="{{ route('welcome') }}">Return home</a>
    </footer>

    <script>
        (() => {
            const mainPhoto = document.getElementById('mainPhoto');
            const thumbnails = Array.from(
                document.querySelectorAll('[data-thumbnail]')
            );
            const counter = document.getElementById('photoCounter');
            const controls = document.getElementById('galleryButtons');

            let currentPhoto = 0;

            function showPhoto(index) {
                if (!mainPhoto || thumbnails.length === 0) {
                    return;
                }

                currentPhoto =
                    (index + thumbnails.length) % thumbnails.length;

                mainPhoto.src = thumbnails[currentPhoto].href;

                thumbnails.forEach((thumbnail, position) => {
                    const active = position === currentPhoto;

                    thumbnail.classList.toggle('is-active', active);

                    if (active) {
                        thumbnail.setAttribute('aria-current', 'true');
                    } else {
                        thumbnail.removeAttribute('aria-current');
                    }
                });

                if (counter) {
                    counter.textContent =
                        (currentPhoto + 1) + ' / ' +
                        thumbnails.length + ' photos';
                }
            }

            thumbnails.forEach((thumbnail, index) => {
                thumbnail.addEventListener('click', (event) => {
                    if (!mainPhoto) {
                        return;
                    }

                    event.preventDefault();
                    showPhoto(index);
                });
            });

            if (controls && thumbnails.length > 1) {
                controls.hidden = false;

                document.getElementById('previousPhoto')
                    .addEventListener('click', () => {
                        showPhoto(currentPhoto - 1);
                    });

                document.getElementById('nextPhoto')
                    .addEventListener('click', () => {
                        showPhoto(currentPhoto + 1);
                    });
            }

            const form = document.getElementById('bookingForm');
            const checkIn = document.getElementById('checkIn');
            const checkOut = document.getElementById('checkOut');
            const submitButton = document.getElementById('submitBooking');

            const nightsLabel = document.getElementById('summaryNights');
            const totalLabel = document.getElementById('summaryTotal');
            const arrivalLabel = document.getElementById('summaryCheckIn');
            const departureLabel = document.getElementById('summaryCheckOut');

            const rateMinor = Number(form.dataset.rateMinor);
            const today = checkIn.min;
            const initiallyDisabled = submitButton.disabled;

            let submitting = false;

            const moneyFormatter = new Intl.NumberFormat('en-BD', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            const dateFormatter = new Intl.DateTimeFormat('en-GB', {
                day: 'numeric',
                month: 'short',
                year: 'numeric',
                timeZone: 'UTC'
            });

            function parseDate(value) {
                if (!/^\d{4}-\d{2}-\d{2}$/.test(value)) {
                    return null;
                }

                const parts = value.split('-').map(Number);
                const timestamp = Date.UTC(parts[0], parts[1] - 1, parts[2]);
                const date = new Date(timestamp);

                if (
                    date.getUTCFullYear() !== parts[0] ||
                    date.getUTCMonth() !== parts[1] - 1 ||
                    date.getUTCDate() !== parts[2]
                ) {
                    return null;
                }

                return timestamp;
            }

            function updateEstimate() {
                const start = parseDate(checkIn.value);
                const end = parseDate(checkOut.value);
                const minimumStart = parseDate(today);

                checkOut.setCustomValidity('');

                const minimumDeparture = start !== null
                    ? start + 86400000
                    : minimumStart !== null
                        ? minimumStart + 86400000
                        : null;

                if (minimumDeparture !== null) {
                    checkOut.min = new Date(minimumDeparture)
                        .toISOString()
                        .slice(0, 10);
                }

                arrivalLabel.textContent = start !== null
                    ? dateFormatter.format(new Date(start))
                    : 'Choose a date';

                departureLabel.textContent = end !== null
                    ? dateFormatter.format(new Date(end))
                    : 'Choose a date';

                if (start === null || end === null) {
                    nightsLabel.textContent = '—';
                    totalLabel.textContent = '—';
                    return;
                }

                const nights = Math.round((end - start) / 86400000);

                if (nights <= 0) {
                    checkOut.setCustomValidity(
                        'Check-out must be after check-in.'
                    );
                    nightsLabel.textContent = 'Check your dates';
                    totalLabel.textContent = '—';
                    return;
                }

                if (checkIn.value < today) {
                    nightsLabel.textContent = 'Check your dates';
                    totalLabel.textContent = '—';
                    return;
                }

                nightsLabel.textContent =
                    nights + (nights === 1 ? ' night' : ' nights');

                totalLabel.textContent = '৳ ' +
                    moneyFormatter.format((rateMinor * nights) / 100);
            }

            checkIn.addEventListener('input', updateEstimate);
            checkOut.addEventListener('input', updateEstimate);

            form.addEventListener('submit', (event) => {
                if (submitting || initiallyDisabled) {
                    event.preventDefault();
                    return;
                }

                updateEstimate();

                if (!form.reportValidity()) {
                    event.preventDefault();
                    return;
                }

                submitting = true;
                submitButton.disabled = true;
                submitButton.textContent = 'Creating your booking…';
                form.setAttribute('aria-busy', 'true');
            });

            window.addEventListener('pageshow', () => {
                submitting = false;
                submitButton.disabled = initiallyDisabled;
                submitButton.textContent = 'Continue to payment →';
                form.removeAttribute('aria-busy');
                updateEstimate();
            });

            updateEstimate();
        })();
    </script>
</body>
</html>