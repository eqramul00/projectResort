@php
    $phone = '01700000000';
    $email = 'hello@mclovinresort.com';
    $location = 'McLovin Valley, Rangamati, Bangladesh';

    $facebook = 'https://www.facebook.com/profile.php?id=100078951246241';
    $whatsapp = 'https://wa.me/8801700000000';

    $logo = asset('images/logo.jpg');

    $heroImage = null;

    foreach (['image/cover.jpg', 'images/cover.jpg'] as $imagePath) {
        if (is_file(public_path($imagePath))) {
            $heroImage = asset($imagePath);
            break;
        }
    }

    $featuredRooms = collect($rooms ?? [])->take(6);

    $currentUser = auth()->user();
    $isGuest = $currentUser && $currentUser->isGuest();

    $isStaff = $currentUser && $currentUser->hasAnyRole([
        'admin',
        'manager',
        'reception',
    ]);
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>McLovin Resort | A Little Closer to Nature</title>

    <meta
        name="description"
        content="Explore rooms at McLovin Resort, plan your stay and manage your reservations through your guest account."
    >

    <style>
        :root {
            --forest: #173c32;
            --forest-dark: #102b24;
            --cream: #f7f5ef;
            --paper: #ffffff;
            --ink: #233830;
            --muted: #627068;
            --gold: #dfbf7a;
            --line: #dde3dc;
            --radius: 22px;
            --max: 1200px;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 100px;
        }

        body {
            margin: 0;
            background: var(--cream);
            color: var(--ink);
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.6;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        img {
            display: block;
            max-width: 100%;
        }

        button {
            font: inherit;
        }

        button,
        a {
            -webkit-tap-highlight-color: transparent;
        }

        h1,
        h2,
        h3,
        p {
            margin-top: 0;
        }

        h1,
        h2,
        h3 {
            line-height: 1.12;
        }

        h1,
        h2 {
            font-family: Georgia, 'Times New Roman', serif;
            font-weight: 400;
        }

        .container {
            width: min(var(--max), calc(100% - 48px));
            margin-inline: auto;
        }

        .section {
            padding: 90px 0;
        }

        .eyebrow {
            display: block;
            margin-bottom: 16px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .muted {
            color: var(--muted);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: 48px;
            padding: 12px 22px;
            border: 1px solid transparent;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            text-align: center;
            cursor: pointer;
            transition:
                background .2s ease,
                transform .2s ease,
                box-shadow .2s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-green {
            background: var(--forest);
            color: #ffffff;
        }

        .btn-green:hover {
            background: var(--forest-dark);
        }

        .btn-gold {
            background: var(--gold);
            color: var(--forest-dark);
        }

        .btn-gold:hover {
            background: #edcf92;
        }

        .btn-outline {
            border-color: var(--line);
            background: transparent;
            color: var(--forest);
        }

        .btn-white-outline {
            border-color: rgba(255,255,255,.45);
            background: rgba(16,43,36,.2);
            color: #ffffff;
        }

        .btn-white-outline:hover {
            background: rgba(255,255,255,.15);
        }

        .actions {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .skip-link {
            position: fixed;
            top: -100px;
            left: 16px;
            padding: 12px 18px;
            background: #ffffff;
            color: var(--forest);
            z-index: 1000;
        }

        .skip-link:focus {
            top: 12px;
        }

        :focus-visible {
            outline: 3px solid #af761f;
            outline-offset: 4px;
        }

        /* Header */

        .site-header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(247,245,239,.97);
            border-bottom: 1px solid var(--line);
        }

        .nav {
            min-height: 88px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .brand-logo {
            width: 46px;
            height: 46px;
            object-fit: contain;
            border-radius: 12px;
            background: #ffffff;
        }

        .brand-name {
            display: block;
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 23px;
            line-height: 1.2;
        }

        .brand-subtitle {
            display: block;
            margin-top: 5px;
            color: var(--muted);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .desktop-nav {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
            min-height: 44px;
            font-size: 14px;
            font-weight: 700;
        }

        .nav-link:hover {
            color: #8d641f;
        }

        .nav-divider {
            width: 1px;
            height: 24px;
            background: var(--line);
        }

        .logout-form {
            margin: 0;
        }

        .logout-button {
            border: 0;
            background: none;
            color: var(--muted);
            cursor: pointer;
            padding: 10px 0;
            font-size: 14px;
        }

        .mobile-menu {
            display: none;
        }

        /* Hero */

        .hero {
            position: relative;
            min-height: 690px;
            min-height: min(790px, 88svh);
            display: flex;
            align-items: center;
            overflow: hidden;
            background:
                radial-gradient(circle at 80% 20%, #547460, transparent 45%),
                linear-gradient(120deg, #102b24, #365a45);
            color: #ffffff;
        }

        .hero-photo {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                90deg,
                rgba(9,28,22,.86) 0%,
                rgba(9,28,22,.58) 50%,
                rgba(9,28,22,.18) 100%
            );
        }

        .hero-content {
            position: relative;
            z-index: 1;
            padding: 90px 0 100px;
        }

        .hero .eyebrow {
            color: #f0d59f;
        }

        .hero h1 {
            max-width: 760px;
            margin-bottom: 24px;
            font-size: clamp(48px, 6.7vw, 88px);
            letter-spacing: -2px;
            line-height: 1.04;
        }

        .hero h1 em {
            color: #efd398;
            font-weight: 400;
        }

        .hero-description {
            max-width: 510px;
            color: #e3e9e1;
            font-size: 17px;
            line-height: 1.85;
            margin-bottom: 32px;
        }

        .hero-note {
            margin: 26px 0 0;
            color: #d8e3d9;
            font-size: 13px;
        }

        .hero-bottom {
            position: absolute;
            bottom: 25px;
            left: 0;
            right: 0;
            z-index: 1;
        }

        .hero-bottom-inner {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            color: #e3e9e1;
            font-size: 12px;
            letter-spacing: .5px;
        }

        /* Intro strip */

        .intro-strip {
            border-bottom: 1px solid var(--line);
            background: #eeeee5;
        }

        .intro-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .intro-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 28px 24px;
        }

        .intro-item + .intro-item {
            border-left: 1px solid #d9dfd5;
        }

        .intro-number {
            color: #7b876e;
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 30px;
        }

        .intro-item strong {
            display: block;
            font-size: 14px;
        }

        .intro-item small {
            color: var(--muted);
            font-size: 12px;
        }

        /* Section headings */

        .section-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 28px;
            margin-bottom: 34px;
        }

        .section-heading h2 {
            margin-bottom: 0;
            font-size: clamp(34px, 4vw, 52px);
            letter-spacing: -1px;
        }

        .section-heading .eyebrow {
            color: #66784d;
        }

        .section-description {
            max-width: 400px;
            margin-bottom: 0;
            color: var(--muted);
            font-size: 15px;
        }

        /* Room cards */

        .room-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 24px;
        }

        .room-card {
            min-width: 0;
            overflow: hidden;
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            transition: box-shadow .2s ease, transform .2s ease;
        }

        .room-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 35px rgba(23,60,50,.08);
        }

        .room-media {
            position: relative;
            aspect-ratio: 4 / 3;
            overflow: hidden;
            background: #e1e6dc;
        }

        .room-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s ease;
        }

        .room-card:hover .room-image {
            transform: scale(1.04);
        }

        .room-placeholder {
            height: 100%;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #dce4d7, #bccbb6);
            color: var(--forest);
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 23px;
        }

        .room-type {
            position: absolute;
            top: 16px;
            left: 16px;
            max-width: calc(100% - 32px);
            padding: 7px 12px;
            border-radius: 7px;
            background: rgba(255,255,255,.94);
            color: var(--forest);
            font-size: 11px;
            font-weight: 700;
        }

        .room-body {
            padding: 24px;
        }

        .room-number {
            margin-bottom: 8px;
            color: var(--muted);
            font-size: 12px;
        }

        .room-body h3 {
            margin-bottom: 18px;
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 27px;
            font-weight: 400;
            overflow-wrap: anywhere;
        }

        .room-price {
            padding-top: 18px;
            border-top: 1px solid var(--line);
            margin-bottom: 20px;
        }

        .room-price strong {
            font-size: 24px;
            color: var(--forest);
        }

        .room-price span {
            font-size: 12px;
            color: var(--muted);
        }

        .room-body .btn {
            width: 100%;
        }

        .room-unavailable {
            margin: 0;
            min-height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0f1eb;
            color: #656e63;
            border-radius: 10px;
            font-size: 13px;
        }

        .rooms-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .rooms-footer p {
            margin: 0;
            color: var(--muted);
            font-size: 13px;
            max-width: 650px;
        }

        .empty-state {
            padding: 40px;
            background: #ffffff;
            border: 1px solid var(--line);
            border-radius: var(--radius);
        }

        /* About */

        .about {
            background: #eaece2;
        }

        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 70px;
            align-items: center;
        }

        .about-photo {
            position: relative;
            aspect-ratio: 5 / 6;
            max-height: 570px;
            border-radius: 24px;
            overflow: hidden;
            background: linear-gradient(145deg, #789477, #254b3a);
        }

        .about-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .about-caption {
            position: absolute;
            bottom: 24px;
            left: 24px;
            right: 24px;
            padding: 18px;
            background: rgba(247,245,239,.94);
            border-radius: 12px;
            font-family: Georgia, 'Times New Roman', serif;
            font-size: 22px;
        }

        .about-copy h2 {
            font-size: clamp(34px, 4vw, 54px);
            letter-spacing: -1px;
        }

        .about-copy > p {
            color: #536353;
            line-height: 1.9;
        }

        .about-list {
            margin: 26px 0;
            padding: 0;
            list-style: none;
        }

        .about-list li {
            padding: 16px 0;
            border-bottom: 1px solid #cdd6c6;
        }

        .about-list strong {
            display: block;
            margin-bottom: 4px;
            font-size: 15px;
        }

        .about-list span {
            color: #536353;
            font-size: 14px;
        }

        /* Booking steps */

        .steps {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 30px;
        }

        .step {
            padding: 28px;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            background: #ffffff;
        }

        .step-number {
            display: inline-flex;
            width: 42px;
            height: 42px;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #edf0e5;
            color: var(--forest);
            margin-bottom: 24px;
            font-size: 13px;
            font-weight: 700;
        }

        .step h3 {
            font-size: 18px;
            margin-bottom: 12px;
        }

        .step p {
            color: var(--muted);
            font-size: 14px;
            margin-bottom: 0;
        }

        /* Account banner */

        .account-banner {
            padding: 42px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
            border-radius: 24px;
            background: var(--forest);
            color: #ffffff;
            margin-top: 40px;
        }

        .account-banner h3 {
            margin-bottom: 10px;
            font-family: Georgia, 'Times New Roman', serif;
            font-weight: 400;
            font-size: 30px;
        }

        .account-banner p {
            margin: 0;
            color: #d4e0d6;
            max-width: 560px;
            font-size: 14px;
        }

        .account-banner .actions {
            flex-shrink: 0;
        }

        /* Contact */

        .contact {
            background: var(--forest-dark);
            color: #ffffff;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
        }

        .contact h2 {
            font-size: clamp(36px, 4vw, 54px);
            margin-bottom: 24px;
        }

        .contact .eyebrow {
            color: var(--gold);
        }

        .contact-intro {
            color: #c5d3c9;
            max-width: 440px;
        }

        .contact-details {
            margin: 0;
        }

        .contact-details > div {
            padding: 20px 0;
            border-bottom: 1px solid rgba(255,255,255,.15);
        }

        .contact-details > div:first-child {
            padding-top: 0;
        }

        .contact-details dt {
            margin-bottom: 7px;
            color: #aebfb2;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .contact-details dd {
            margin: 0;
            font-size: 18px;
            overflow-wrap: anywhere;
        }

        .contact-details a:hover {
            color: var(--gold);
        }

        .contact-social {
            margin-top: 25px;
            display: flex;
            gap: 22px;
            flex-wrap: wrap;
            font-size: 14px;
        }

        /* Footer and mobile booking button */

        .site-footer {
            padding: 30px 0;
            background: #0c211b;
            color: #c3d0c6;
        }

        .footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        .footer-inner p {
            margin: 0;
            font-size: 12px;
        }

        .footer-links {
            display: flex;
            gap: 20px;
            font-size: 12px;
        }

        .mobile-booking {
            display: none;
        }

        /* Tablet */

        @media (max-width: 1100px) {
            .desktop-nav {
                gap: 16px;
            }

            .brand-name {
                font-size: 21px;
            }

            .room-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .about-grid {
                gap: 40px;
            }

            .account-banner {
                align-items: flex-start;
                flex-direction: column;
            }
        }

        @media (max-width: 960px) {
            .desktop-nav {
                display: none;
            }

            .mobile-menu {
                display: block;
                position: relative;
            }

            .mobile-menu summary {
                list-style: none;
                min-height: 46px;
                min-width: 76px;
                padding: 10px 14px;
                border: 1px solid var(--line);
                border-radius: 10px;
                font-size: 14px;
                font-weight: 700;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .mobile-menu summary::-webkit-details-marker {
                display: none;
            }

            .mobile-menu[open] summary {
                background: var(--forest);
                color: #ffffff;
            }

            .mobile-links {
                position: absolute;
                top: calc(100% + 16px);
                right: 0;
                width: min(330px, calc(100vw - 48px));
                padding: 16px;
                display: grid;
                gap: 5px;
                background: var(--cream);
                border: 1px solid var(--line);
                border-radius: 16px;
                box-shadow: 0 20px 40px rgba(16,43,36,.15);
                max-height: 70vh;
                overflow-y: auto;
            }

            .mobile-links > a:not(.btn),
            .mobile-links .logout-button {
                display: block;
                width: 100%;
                padding: 12px;
                min-height: 46px;
                text-align: left;
                color: var(--ink);
                font-size: 14px;
            }

            .mobile-links .btn {
                margin-top: 8px;
            }

            .mobile-links hr {
                border: 0;
                border-top: 1px solid var(--line);
                width: 100%;
            }

            .hero {
                min-height: 650px;
            }

            .about-grid,
            .contact-grid {
                grid-template-columns: 1fr;
            }

            .about-photo {
                aspect-ratio: 16 / 10;
                max-height: 420px;
            }

            .contact-grid {
                gap: 40px;
            }

            .steps {
                gap: 16px;
            }
        }

        /* Mobile */

        @media (max-width: 640px) {
            html {
                scroll-padding-top: 86px;
            }

            .container {
                width: calc(100% - 36px);
            }

            .section {
                padding: 60px 0;
            }

            .nav {
                min-height: 76px;
                gap: 12px;
            }

            .brand {
                gap: 9px;
            }

            .brand-logo {
                width: 38px;
                height: 38px;
            }

            .brand-name {
                font-size: 20px;
            }

            .brand-subtitle {
                font-size: 8px;
                letter-spacing: 1.3px;
            }

            .hero {
                min-height: 610px;
            }

            .hero::after {
                background: linear-gradient(
                    90deg,
                    rgba(9,28,22,.84),
                    rgba(9,28,22,.53)
                );
            }

            .hero-content {
                padding: 65px 0 100px;
            }

            .hero h1 {
                font-size: clamp(43px, 11vw, 64px);
                letter-spacing: -1.4px;
            }

            .hero-description {
                font-size: 15px;
                max-width: 350px;
            }

            .hero-content .actions {
                align-items: stretch;
            }

            .hero-content .actions .btn {
                flex: 1;
                padding-inline: 15px;
            }

            .hero-bottom-inner {
                font-size: 11px;
            }

            .hero-bottom-inner span:last-child {
                display: none;
            }

            .intro-grid {
                grid-template-columns: 1fr;
            }

            .intro-item {
                padding: 18px 0;
            }

            .intro-item + .intro-item {
                border-left: 0;
                border-top: 1px solid #d9dfd5;
            }

            .section-heading {
                align-items: flex-start;
                flex-direction: column;
                gap: 16px;
            }

            .room-grid,
            .steps {
                grid-template-columns: 1fr;
            }

            .room-body {
                padding: 22px;
            }

            .room-media {
                aspect-ratio: 16 / 11;
            }

            .about-grid {
                gap: 30px;
            }

            .about-caption {
                bottom: 16px;
                left: 16px;
                right: 16px;
                font-size: 18px;
                padding: 14px;
            }

            .account-banner {
                padding: 28px;
            }

            .account-banner .actions {
                width: 100%;
            }

            .account-banner .btn {
                flex: 1;
            }

            .contact-details dd {
                font-size: 16px;
            }

            .site-footer {
                padding-bottom: calc(110px + env(safe-area-inset-bottom));
            }

            .mobile-booking {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                position: fixed;
                z-index: 90;
                left: 12px;
                right: 12px;
                bottom: calc(12px + env(safe-area-inset-bottom));
                padding: 12px 14px;
                border-radius: 16px;
                background: var(--cream);
                border: 1px solid var(--line);
                box-shadow: 0 8px 35px rgba(0,0,0,.2);
            }

            .mobile-booking strong {
                display: block;
                font-size: 13px;
            }

            .mobile-booking small {
                display: block;
                color: var(--muted);
                font-size: 11px;
            }

            .mobile-booking .btn {
                min-height: 44px;
                padding: 10px 16px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            *,
            *::before,
            *::after {
                transition: none !important;
            }
        }
    </style>
</head>

<body>
    <a href="#main-content" class="skip-link">Skip to content</a>

    <header class="site-header">
        <div class="container nav">
            <a href="{{ route('welcome') }}"
               class="brand"
               aria-label="McLovin Resort home">
                <img
                    src="{{ $logo }}"
                    alt=""
                    width="46"
                    height="46"
                    class="brand-logo"
                >

                <span>
                    <span class="brand-name">McLovin Resort</span>
                    <span class="brand-subtitle">A little closer to nature</span>
                </span>
            </a>

            <nav class="desktop-nav" aria-label="Main navigation">
                <a href="#rooms" class="nav-link">Our rooms</a>
                <a href="#about" class="nav-link">The resort</a>
                <a href="#contact" class="nav-link">Contact</a>

                <span class="nav-divider" aria-hidden="true"></span>

                @if($isGuest)
                    <a href="{{ route('guest.dashboard') }}" class="nav-link">
                        My Bookings
                    </a>

                    <form method="POST"
                          action="{{ route('guest.logout') }}"
                          class="logout-form">
                        @csrf
                        <button type="submit" class="logout-button">
                            Log out
                        </button>
                    </form>
                @elseif($isStaff)
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        Staff Dashboard
                    </a>
                @elseif(!$currentUser)
                    <a href="{{ route('guest.login') }}" class="nav-link">
                        Guest Login
                    </a>
                @endif

                <a href="{{ route('public.book') }}" class="btn btn-green">
                    Book a stay <span aria-hidden="true">↗</span>
                </a>
            </nav>

            <details class="mobile-menu" id="mobileMenu">
                <summary>Menu</summary>

                <nav class="mobile-links" aria-label="Mobile navigation">
                    <a href="#rooms">Our rooms</a>
                    <a href="#about">The resort</a>
                    <a href="#how-it-works">How to book</a>
                    <a href="#contact">Contact</a>

                    <hr>

                    @if($isGuest)
                        <a href="{{ route('guest.dashboard') }}">
                            My Bookings
                        </a>

                        <form method="POST"
                              action="{{ route('guest.logout') }}"
                              class="logout-form">
                            @csrf
                            <button type="submit" class="logout-button">
                                Log out
                            </button>
                        </form>
                    @elseif($isStaff)
                        <a href="{{ route('dashboard') }}">
                            Staff Dashboard
                        </a>
                    @elseif(!$currentUser)
                        <a href="{{ route('guest.login') }}">Guest Login</a>
                        <a href="{{ route('guest.register') }}">Create Account</a>
                    @endif

                    <a href="{{ route('public.book') }}" class="btn btn-green">
                        Book a stay
                    </a>
                </nav>
            </details>
        </div>
    </header>

    <main id="main-content">
        <section class="hero" aria-labelledby="hero-title">
            @if($heroImage)
                <img
                    src="{{ $heroImage }}"
                    alt=""
                    class="hero-photo"
                    fetchpriority="high"
                >
            @endif

            <div class="container hero-content">
                <span class="eyebrow">Slow down. Settle in.</span>

                <h1 id="hero-title">
                    A change of scenery.<br>
                    A little more <em>peace.</em>
                </h1>

                <p class="hero-description">
                    Leave the everyday behind. Find your room, plan your
                    escape, and make time for a quieter kind of stay
                    at McLovin Resort.
                </p>

                <div class="actions">
                    <a href="{{ route('public.book') }}" class="btn btn-gold">
                        Plan your stay <span aria-hidden="true">↗</span>
                    </a>

                    <a href="#rooms" class="btn btn-white-outline">
                        Explore rooms
                    </a>
                </div>

                <p class="hero-note">
                    Book directly with us. Our team will review and confirm
                    your reservation.
                </p>
            </div>

            <div class="hero-bottom">
                <div class="container hero-bottom-inner">
                    <span>McLovin Resort · Bangladesh</span>
                    <span>Discover your next escape ↓</span>
                </div>
            </div>
        </section>

        <div class="intro-strip">
            <div class="container intro-grid">
                <div class="intro-item">
                    <span class="intro-number" aria-hidden="true">01</span>
                    <div>
                        <strong>Find your room</strong>
                        <small>Explore rooms and nightly prices</small>
                    </div>
                </div>

                <div class="intro-item">
                    <span class="intro-number" aria-hidden="true">02</span>
                    <div>
                        <strong>Choose your dates</strong>
                        <small>Availability checked when you book</small>
                    </div>
                </div>

                <div class="intro-item">
                    <span class="intro-number" aria-hidden="true">03</span>
                    <div>
                        <strong>Keep track of your stay</strong>
                        <small>View account bookings in one place</small>
                    </div>
                </div>
            </div>
        </div>

        <section class="section" id="rooms" aria-labelledby="rooms-title">
            <div class="container">
                <div class="section-heading">
                    <div>
                        <span class="eyebrow">Your own space to unwind</span>
                        <h2 id="rooms-title">Find your favourite room.</h2>
                    </div>

                    <p class="section-description">
                        Browse our rooms, then choose your dates.
                        Availability depends on your selected stay.
                    </p>
                </div>

                @if($featuredRooms->isNotEmpty())
                    <div class="room-grid">
                        @foreach($featuredRooms as $room)
                            @php
                                $firstImage = $room->images->first();
                                $roomImage = null;

                                if ($firstImage && $firstImage->image_path) {
                                    $storedPath = ltrim(
                                        $firstImage->image_path,
                                        '/'
                                    );

                                    $roomImage = str_starts_with(
                                        $storedPath,
                                        'storage/'
                                    )
                                        ? asset($storedPath)
                                        : asset('storage/' . $storedPath);
                                }

                                $acceptsBookings = in_array(
                                    $room->status,
                                    ['available', 'occupied'],
                                    true
                                );

                                $hasPrice = is_numeric($room->price)
                                    && (float) $room->price >= 0;
                            @endphp

                            <article class="room-card">
                                <div class="room-media">
                                    @if($roomImage)
                                        <img
                                            src="{{ $roomImage }}"
                                            alt="{{ $room->name }}"
                                            class="room-image"
                                            loading="lazy"
                                            decoding="async"
                                            width="600"
                                            height="450"
                                        >
                                    @else
                                        <div class="room-placeholder">
                                            McLovin Resort
                                        </div>
                                    @endif

                                    <span class="room-type">
                                        {{ $room->type ?: 'Guest room' }}
                                    </span>
                                </div>

                                <div class="room-body">
                                    <p class="room-number">
                                        Room {{ $room->number }}
                                    </p>

                                    <h3>{{ $room->name }}</h3>

                                    <div class="room-price">
                                        @if($hasPrice)
                                            <strong>
                                                ৳ {{ number_format((float) $room->price, 2) }}
                                            </strong>
                                            <span>/ night</span>
                                        @else
                                            <span>Contact us for pricing</span>
                                        @endif
                                    </div>

                                    @if($acceptsBookings && $hasPrice)
                                        <a
                                            href="{{ route('public.book', ['room' => $room->id]) }}"
                                            class="btn btn-green"
                                            aria-label="Choose dates for {{ $room->name }}"
                                        >
                                            Choose dates
                                            <span aria-hidden="true">↗</span>
                                        </a>
                                    @else
                                        <p class="room-unavailable">
                                            Currently unavailable
                                        </p>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <h3>Our room listings are being updated.</h3>
                        <p class="muted">
                            Contact our team for help planning your stay.
                        </p>
                        <a href="#contact" class="btn btn-green">
                            Contact the resort
                        </a>
                    </div>
                @endif

                <div class="rooms-footer">
                    <p>
                        Nightly prices shown are current room prices.
                        Your agreed nightly rate is saved when your
                        booking is created.
                    </p>

                    <a href="{{ route('public.book') }}" class="btn btn-outline">
                        Open booking form <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>
        </section>

        <section class="section about" id="about" aria-labelledby="about-title">
            <div class="container about-grid">
                <div class="about-photo">
                    @if($heroImage)
                        <img
                            src="{{ $heroImage }}"
                            alt="The surroundings at McLovin Resort"
                            loading="lazy"
                            decoding="async"
                            width="700"
                            height="840"
                        >
                    @endif

                    <div class="about-caption">
                        A slower pace. A different perspective.
                    </div>
                </div>

                <div class="about-copy">
                    <span class="eyebrow">Welcome to McLovin</span>

                    <h2 id="about-title">
                        Make a little room<br>for yourself.
                    </h2>

                    <p>
                        A good getaway starts with a little breathing space.
                        Whether you are planning time with family, a trip
                        together, or a break from your routine, start by
                        finding a room that suits your stay.
                    </p>

                    <ul class="about-list">
                        <li>
                            <strong>A simple way to plan</strong>
                            <span>
                                Choose your room and dates through our
                                direct booking form.
                            </span>
                        </li>

                        <li>
                            <strong>Your reservation, easy to find</strong>
                            <span>
                                Sign in before booking to keep your new
                                reservations in your guest account.
                            </span>
                        </li>

                        <li>
                            <strong>A team you can contact</strong>
                            <span>
                                Ask us about your visit, payment review
                                or booking details.
                            </span>
                        </li>
                    </ul>

                    <a href="#contact" class="btn btn-green">
                        Talk to our team <span aria-hidden="true">↗</span>
                    </a>
                </div>
            </div>
        </section>

        <section
            class="section"
            id="how-it-works"
            aria-labelledby="steps-title"
        >
            <div class="container">
                <div class="section-heading">
                    <div>
                        <span class="eyebrow">From planning to arriving</span>
                        <h2 id="steps-title">Your stay starts here.</h2>
                    </div>
                    <p class="section-description">
                        A few clear steps, with your booking status
                        visible along the way.
                    </p>
                </div>

                <div class="steps">
                    <article class="step">
                        <span class="step-number">01</span>
                        <h3>Choose your stay</h3>
                        <p>
                            Open the booking form, select your room
                            and enter your arrival and departure dates.
                        </p>
                    </article>

                    <article class="step">
                        <span class="step-number">02</span>
                        <h3>Submit payment details</h3>
                        <p>
                            Follow the payment instructions and upload
                            your receipt. A submission is awaiting review,
                            not yet a verified payment.
                        </p>
                    </article>

                    <article class="step">
                        <span class="step-number">03</span>
                        <h3>Look for confirmation</h3>
                        <p>
                            Our team reviews payment and confirms your
                            reservation. Account guests can check the
                            status in My Bookings.
                        </p>
                    </article>
                </div>

                <div class="account-banner">
                    @if($isGuest)
                        <div>
                            <h3>Your next stay, all in one place.</h3>
                            <p>
                                View your account bookings and check
                                their reservation and payment status.
                            </p>
                        </div>

                        <a href="{{ route('guest.dashboard') }}"
                           class="btn btn-gold">
                            My Bookings <span aria-hidden="true">↗</span>
                        </a>
                    @elseif($isStaff)
                        <div>
                            <h3>Welcome back to McLovin.</h3>
                            <p>
                                Open your staff dashboard to manage
                                the resort’s day-to-day activity.
                            </p>
                        </div>

                        <a href="{{ route('dashboard') }}"
                           class="btn btn-gold">
                            Staff Dashboard
                        </a>
                    @elseif(!$currentUser)
                        <div>
                            <h3>A little less searching next time.</h3>
                            <p>
                                Create a guest account, then book while
                                signed in to keep your new reservations
                                together.
                            </p>
                        </div>

                        <div class="actions">
                            <a href="{{ route('guest.register') }}"
                               class="btn btn-gold">
                                Create Account
                            </a>

                            <a href="{{ route('guest.login') }}"
                               class="btn btn-white-outline">
                                Guest Login
                            </a>
                        </div>
                    @else
                        <div>
                            <h3>Let us help plan your stay.</h3>
                            <p>
                                Contact our team if you need assistance
                                with your account or reservation.
                            </p>
                        </div>

                        <a href="#contact" class="btn btn-gold">
                            Contact us
                        </a>
                    @endif
                </div>
            </div>
        </section>

        <section
            class="section contact"
            id="contact"
            aria-labelledby="contact-title"
        >
            <div class="container contact-grid">
                <div>
                    <span class="eyebrow">Let’s plan something good</span>
                    <h2 id="contact-title">
                        Your getaway.<br>Our warm welcome.
                    </h2>

                    <p class="contact-intro">
                        Have a question before booking? Get in touch
                        for help with room options, your reservation
                        or planning your visit.
                    </p>

                    <div class="actions">
                        <a href="tel:{{ $phone }}" class="btn btn-gold">
                            Call the resort
                        </a>

                        <a
                            href="{{ $whatsapp }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn btn-white-outline"
                        >
                            WhatsApp
                            <span aria-hidden="true">↗</span>
                        </a>
                    </div>
                </div>

                <div>
                    <dl class="contact-details">
                        <div>
                            <dt>Find us</dt>
                            <dd>{{ $location }}</dd>
                        </div>

                        <div>
                            <dt>Give us a call</dt>
                            <dd>
                                <a href="tel:{{ $phone }}">
                                    {{ $phone }}
                                </a>
                            </dd>
                        </div>

                        <div>
                            <dt>Send a message</dt>
                            <dd>
                                <a href="mailto:{{ $email }}">
                                    {{ $email }}
                                </a>
                            </dd>
                        </div>
                    </dl>

                    <div class="contact-social">
                        <a
                            href="{{ $facebook }}"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            Facebook <span aria-hidden="true">↗</span>
                        </a>

                        <a href="{{ route('public.book') }}">
                            Booking form <span aria-hidden="true">↗</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
            <p>© {{ date('Y') }} McLovin Resort. All rights reserved.</p>

            <nav class="footer-links" aria-label="Footer navigation">
                <a href="#rooms">Rooms</a>
                <a href="#contact">Contact</a>

                @if(!$currentUser)
                    <a href="{{ route('login') }}">Staff Login</a>
                @elseif($isStaff)
                    <a href="{{ route('dashboard') }}">Staff Dashboard</a>
                @elseif($isGuest)
                    <a href="{{ route('guest.dashboard') }}">My Bookings</a>
                @endif
            </nav>
        </div>
    </footer>

    <div class="mobile-booking" aria-label="Quick booking">
        <div>
            <strong>A stay worth slowing down for.</strong>
            <small>Choose your room and dates</small>
        </div>

        <a href="{{ route('public.book') }}" class="btn btn-green">
            Book a stay
        </a>
    </div>

    <script>
        (() => {
            const menu = document.getElementById('mobileMenu');

            if (!menu) {
                return;
            }

            menu.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', () => {
                    menu.open = false;
                });
            });

            document.addEventListener('click', (event) => {
                if (menu.open && !menu.contains(event.target)) {
                    menu.open = false;
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && menu.open) {
                    menu.open = false;

                    const summary = menu.querySelector('summary');

                    if (summary) {
                        summary.focus();
                    }
                }
            });

            const desktopQuery = window.matchMedia('(min-width: 961px)');

            if (typeof desktopQuery.addEventListener === 'function') {
                desktopQuery.addEventListener('change', (event) => {
                    if (event.matches) {
                        menu.open = false;
                    }
                });
            }
        })();
    </script>
</body>
</html>