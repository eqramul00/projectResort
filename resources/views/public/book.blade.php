<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Choose Your Room | McLovin Resort</title>

    <style>
        :root {
            --forest: #173c32;
            --dark: #102b24;
            --cream: #f7f5ef;
            --ink: #233830;
            --muted: #627068;
            --gold: #dfbf7a;
            --line: #dde3dc;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--cream);
            color: var(--ink);
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.6;
        }

        a { color: inherit; text-decoration: none; }
        img { display: block; max-width: 100%; }
        button { font: inherit; }
        [hidden] { display: none !important; }

        .container {
            width: min(1200px, calc(100% - 48px));
            margin-inline: auto;
        }

        header {
            background: var(--cream);
            border-bottom: 1px solid var(--line);
        }

        .nav {
            min-height: 86px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand img {
            width: 46px;
            height: 46px;
            object-fit: contain;
            background: #ffffff;
            border-radius: 12px;
        }

        .brand strong {
            display: block;
            font-family: Georgia, serif;
            font-size: 23px;
            font-weight: 400;
            line-height: 1.2;
        }

        .brand small {
            display: block;
            margin-top: 5px;
            color: var(--muted);
            font-size: 9px;
            letter-spacing: 1.6px;
            text-transform: uppercase;
        }

        .nav-links {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            font-size: 13px;
            font-weight: 700;
        }

        .nav-links a {
            display: inline-flex;
            align-items: center;
            min-height: 44px;
        }

        .nav-links .account {
            padding: 9px 15px;
            background: #e8eee3;
            border-radius: 9px;
        }

        .intro {
            padding: 58px 0 36px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 30px;
        }

        .eyebrow {
            display: block;
            margin-bottom: 15px;
            color: #66784d;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        h1 {
            margin: 0 0 18px;
            font-family: Georgia, serif;
            font-size: clamp(36px, 5vw, 62px);
            font-weight: 400;
            line-height: 1.08;
            letter-spacing: -1.5px;
        }

        .intro p {
            margin: 0;
            max-width: 570px;
            color: var(--muted);
            font-size: 14px;
        }

        .step {
            padding: 12px 18px;
            border: 1px solid #cdd7c6;
            border-radius: 999px;
            color: var(--forest);
            font-size: 12px;
            white-space: nowrap;
            background: #edf0e6;
        }

        .rooms {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 28px;
            padding-bottom: 60px;
        }

        .room-card {
            min-width: 0;
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: 22px;
            overflow: hidden;
        }

        .gallery-main {
            position: relative;
            aspect-ratio: 16 / 10;
            background: #e1e7da;
            display: grid;
            place-items: center;
            color: var(--muted);
        }

        .gallery-main > img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-count {
            position: absolute;
            bottom: 16px;
            right: 16px;
            padding: 6px 11px;
            border-radius: 7px;
            background: rgba(16,43,36,.87);
            color: #ffffff;
            font-size: 11px;
        }

        .room-body {
            padding: 24px;
        }

        .thumbnails {
            display: flex;
            gap: 9px;
            overflow-x: auto;
            padding: 4px 2px 12px;
            margin: -4px 0 17px;
        }

        .thumbnail {
            display: block;
            flex: 0 0 76px;
            width: 76px;
            height: 57px;
            border: 2px solid transparent;
            border-radius: 9px;
            overflow: hidden;
        }

        .thumbnail.is-active {
            border-color: var(--forest);
        }

        .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .room-type {
            color: #67784e;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .room-title-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            margin-top: 10px;
        }

        h2 {
            margin: 0 0 7px;
            font-family: Georgia, serif;
            font-size: 31px;
            font-weight: 400;
            line-height: 1.15;
            overflow-wrap: anywhere;
        }

        .room-number {
            color: var(--muted);
            font-size: 12px;
        }

        .price {
            text-align: right;
            flex-shrink: 0;
        }

        .price strong {
            display: block;
            color: var(--forest);
            font-size: 24px;
        }

        .price small {
            color: var(--muted);
            font-size: 11px;
        }

        .room-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            border-top: 1px solid var(--line);
            margin-top: 23px;
            padding-top: 20px;
        }

        .room-footer p {
            max-width: 220px;
            margin: 0;
            color: var(--muted);
            font-size: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: 48px;
            padding: 12px 19px;
            border: 0;
            border-radius: 10px;
            color: #ffffff;
            background: var(--forest);
            font-size: 13px;
            font-weight: 700;
            text-align: center;
        }

        .btn:hover { background: var(--dark); }

        .empty {
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 60px;
        }

        footer {
            padding: 28px 0;
            background: var(--dark);
            color: #d2ded4;
            font-size: 12px;
        }

        .footer-inner {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }

        :focus-visible {
            outline: 3px solid #ac7624;
            outline-offset: 4px;
        }

        @media (max-width: 800px) {
            .rooms { grid-template-columns: 1fr; }
            .intro { align-items: flex-start; flex-direction: column; }
            .nav { flex-wrap: wrap; padding: 16px 0; }
        }

        @media (max-width: 480px) {
            .container { width: calc(100% - 32px); }
            .intro { padding-top: 35px; }
            .brand strong { font-size: 21px; }
            .nav-links { width: 100%; gap: 16px; }
            .room-body { padding: 20px; }
            .room-title-row { flex-direction: column; gap: 15px; }
            .price { text-align: left; }
            .room-footer { flex-direction: column; align-items: stretch; }
            .room-footer p { max-width: none; }
            .thumbnail { flex-basis: 65px; width: 65px; height: 50px; }
        }
    </style>
</head>

<body>
    <header>
        <div class="container nav">
            <a href="{{ route('welcome') }}" class="brand">
                <img src="{{ asset('images/logo.jpg') }}" alt="" width="46" height="46">
                <span>
                    <strong>McLovin Resort</strong>
                    <small>A little closer to nature</small>
                </span>
            </a>

            <nav class="nav-links" aria-label="Main navigation">
                <a href="{{ route('welcome') }}">Home</a>
                <a href="{{ route('welcome') }}#contact">Contact</a>

                @if(auth()->check() && auth()->user()->isGuest())
                    <a href="{{ route('guest.dashboard') }}" class="account">
                        My Bookings
                    </a>
                @elseif(auth()->check() && auth()->user()->canViewBookings())
                    <a href="{{ route('dashboard') }}" class="account">
                        Staff Dashboard
                    </a>
                @elseif(!auth()->check())
                    <a href="{{ route('guest.login') }}" class="account">
                        Guest Login
                    </a>
                @endif
            </nav>
        </div>
    </header>

    <main class="container">
        <section class="intro">
            <div>
                <span class="eyebrow">Find your own space</span>
                <h1>A room for your<br>kind of getaway.</h1>
                <p>
                    Explore the photos and choose your favourite room.
                    On the next page, select your dates and add your details.
                </p>
            </div>

            <span class="step">Step 1 of 3 · Choose your room</span>
        </section>

        @if($rooms->isNotEmpty())
            <div class="rooms">
                @foreach($rooms as $room)
                    @php
                        $photos = [];

                        foreach ($room->images as $image) {
                            if (!$image->image_path) {
                                continue;
                            }

                            $path = ltrim($image->image_path, '/');

                            $photos[] = str_starts_with($path, 'storage/')
                                ? asset($path)
                                : asset('storage/' . $path);
                        }
                    @endphp

                    <article class="room-card" data-gallery>
                        <div class="gallery-main">
                            @if(count($photos) > 0)
                                <img
                                    src="{{ $photos[0] }}"
                                    alt="{{ $room->name }}"
                                    data-main-image
                                    width="800"
                                    height="500"
                                    loading="lazy"
                                >

                                <span class="photo-count" data-photo-count>
                                    1 / {{ count($photos) }} photos
                                </span>
                            @else
                                Room photos coming soon
                            @endif
                        </div>

                        <div class="room-body">
                            @if(count($photos) > 1)
                                <div class="thumbnails" aria-label="Photos of {{ $room->name }}">
                                    @foreach($photos as $photo)
                                        <a
                                            href="{{ $photo }}"
                                            class="thumbnail {{ $loop->first ? 'is-active' : '' }}"
                                            data-thumbnail
                                            data-photo-number="{{ $loop->iteration }}"
                                            data-photo-total="{{ count($photos) }}"
                                            aria-label="View photo {{ $loop->iteration }} of {{ $room->name }}"
                                            @if($loop->first) aria-current="true" @endif
                                            target="_blank"
                                            rel="noopener noreferrer"
                                        >
                                            <img
                                                src="{{ $photo }}"
                                                alt=""
                                                width="76"
                                                height="57"
                                                loading="lazy"
                                            >
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            <span class="room-type">
                                {{ $room->type ?: 'Guest room' }}
                            </span>

                            <div class="room-title-row">
                                <div>
                                    <h2>{{ $room->name }}</h2>
                                    <span class="room-number">
                                        Room {{ $room->number }}
                                    </span>
                                </div>

                                <div class="price">
                                    <strong>
                                        ৳ {{ number_format((float) $room->price, 2) }}
                                    </strong>
                                    <small>per night</small>
                                </div>
                            </div>

                            <div class="room-footer">
                                <p>
                                    Availability depends on the dates
                                    you choose next.
                                </p>

                                <a
                                    href="{{ route('public.book', ['room' => $room->id]) }}"
                                    class="btn"
                                    aria-label="Select {{ $room->name }} and continue"
                                >
                                    Select room <span aria-hidden="true">→</span>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="empty">
                <h2>Our room listings are being updated.</h2>
                <p>Please contact the resort team for assistance.</p>
                <a href="{{ route('welcome') }}#contact" class="btn">
                    Contact us
                </a>
            </div>
        @endif
    </main>

    <footer>
        <div class="container footer-inner">
            <span>© {{ date('Y') }} McLovin Resort</span>
            <a href="{{ route('welcome') }}">Return to Home</a>
        </div>
    </footer>

    <script>
        document.querySelectorAll('[data-gallery]').forEach((gallery) => {
            const mainImage = gallery.querySelector('[data-main-image]');
            const counter = gallery.querySelector('[data-photo-count]');
            const thumbnails = gallery.querySelectorAll('[data-thumbnail]');

            thumbnails.forEach((thumbnail) => {
                thumbnail.addEventListener('click', (event) => {
                    if (!mainImage) {
                        return;
                    }

                    event.preventDefault();
                    mainImage.src = thumbnail.href;

                    thumbnails.forEach((item) => {
                        item.classList.remove('is-active');
                        item.removeAttribute('aria-current');
                    });

                    thumbnail.classList.add('is-active');
                    thumbnail.setAttribute('aria-current', 'true');

                    if (counter) {
                        counter.textContent =
                            thumbnail.dataset.photoNumber + ' / ' +
                            thumbnail.dataset.photoTotal + ' photos';
                    }
                });
            });
        });
    </script>
</body>
</html>