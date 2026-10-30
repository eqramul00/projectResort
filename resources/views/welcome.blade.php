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

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, viewport-fit=cover"
    >

    <title>McLovin Resort | Less Rush. More Living.</title>

    <meta name="theme-color" content="#193d32">

    <meta
        name="description"
        content="Explore rooms at McLovin Resort, plan your stay and manage your reservations through your guest account."
    >

    <style>
        :root {
            --forest: #193d32;
            --forest-dark: #112d26;
            --cream: #f7f5ef;
            --paper: #fffefa;
            --ink: #243e34;
            --muted: #68776c;
            --gold: #e5bd7c;
            --line: #d9ded4;
            --radius: 18px;
            --max: 1280px;
            --ease: cubic-bezier(.22, 1, .36, 1);
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 110px;
        }

        body {
            margin: 0;
            background: var(--cream);
            color: var(--ink);
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.65;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input {
            font: inherit;
        }

        button,
        a {
            -webkit-tap-highlight-color: transparent;
        }

        button {
            cursor: pointer;
        }

        img {
            display: block;
            max-width: 100%;
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
            line-height: 1.08;
        }

        h1,
        h2 {
            font-family: Georgia, 'Times New Roman', serif;
            font-weight: 400;
        }

        ::selection {
            background: var(--gold);
            color: var(--forest-dark);
        }

        :focus-visible {
            outline: 3px solid #b56c25;
            outline-offset: 5px;
        }

        main {
            overflow: clip;
        }

        .container {
            width: min(var(--max), calc(100% - 96px));
            margin-inline: auto;
        }

        .section {
            padding: 110px 0;
        }

        .eyebrow {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 22px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 2.8px;
            text-transform: uppercase;
        }

        .eyebrow::before {
            content: '';
            width: 27px;
            height: 1px;
            flex-shrink: 0;
            background: currentColor;
        }

        .muted {
            color: var(--muted);
        }

        .actions {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 24px;
            min-height: 50px;
            padding: 13px 23px;
            border: 1px solid transparent;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .2px;
            line-height: 1.5;
            text-align: center;
            transition:
                transform .35s var(--ease),
                background .25s,
                box-shadow .35s;
        }

        .btn span {
            display: inline-block;
            transition: transform .35s var(--ease);
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 24px #112d2612;
        }

        .btn:hover span {
            transform: translate(3px, -2px);
        }

        .btn-green {
            background: var(--forest);
            color: #ffffff;
        }

        .btn-green:hover {
            background: #295744;
        }

        .btn-gold {
            background: var(--gold);
            color: var(--forest-dark);
        }

        .btn-gold:hover {
            background: #f1cd96;
        }

        .btn-outline {
            border-color: #adb8aa;
            background: transparent;
            color: var(--forest);
        }

        .btn-outline:hover {
            background: #e9ece2;
        }

        .btn-white-outline {
            border-color: #ffffff61;
            background: #ffffff06;
            color: #ffffff;
            backdrop-filter: blur(8px);
        }

        .btn-white-outline:hover {
            background: #ffffff17;
        }

        .skip-link {
            position: fixed;
            top: -100px;
            left: 20px;
            padding: 12px 20px;
            z-index: 1000;
            background: #ffffff;
        }

        .skip-link:focus {
            top: 15px;
        }

        /* Header */

        .site-header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: #f7f5eff5;
            border-bottom: 1px solid var(--line);
            backdrop-filter: blur(18px);
            transition: box-shadow .3s;
        }

        .site-header.scrolled {
            box-shadow: 0 6px 24px #112d260c;
        }

        .scroll-progress {
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 2px;
            background: #a98044;
            transform: scaleX(0);
            transform-origin: left;
            pointer-events: none;
        }

        .nav {
            min-height: 92px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .brand-logo {
            width: 44px;
            height: 44px;
            object-fit: contain;
            border-radius: 50%;
            background: #ffffff;
        }

        .brand-mark {
            width: 44px;
            height: 44px;
            border: 1px solid #9ba88b;
            border-radius: 50%;
            display: grid;
            place-items: center;
            font: 25px Georgia, serif;
        }

        .brand-name {
            display: block;
            font: 23px/1.2 Georgia, serif;
            letter-spacing: -.8px;
        }

        .brand-subtitle {
            display: block;
            margin-top: 5px;
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 2.3px;
            text-transform: uppercase;
            color: var(--muted);
        }

        .desktop-nav {
            display: flex;
            align-items: center;
            gap: 27px;
        }

        .nav-link {
            position: relative;
            display: inline-flex;
            align-items: center;
            min-height: 44px;
            font-size: 12px;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 0;
            width: 100%;
            height: 1px;
            background: var(--forest);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .35s var(--ease);
        }

        .nav-link:hover::after {
            transform: scaleX(1);
        }

        .nav-tools {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .access-wrap {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .access-caption {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1.4px;
            color: var(--muted);
        }

        .access-switch {
            display: inline-flex;
            align-items: center;
            border: 1px solid #bac4b6;
            border-radius: 30px;
            padding: 4px;
            background: #eeeee5;
            white-space: nowrap;
        }

        .access-switch a {
            display: grid;
            place-items: center;
            min-height: 34px;
            min-width: 59px;
            padding: 6px 12px;
            border-radius: 24px;
            font-size: 11px;
            font-weight: 700;
            transition: background .25s, color .25s;
        }

        .access-switch a:first-child {
            background: var(--forest);
            color: #ffffff;
        }

        .access-switch a:hover {
            background: #315943;
            color: #ffffff;
        }

        .access-divider {
            padding: 0 2px;
            color: #929e8c;
            font-size: 12px;
        }

        .logout-form {
            margin: 0;
        }

        .logout-button {
            padding: 10px;
            border: 0;
            background: none;
            color: var(--muted);
            font-size: 12px;
            min-height: 44px;
        }

        .account-link {
            max-width: 165px;
            font-size: 12px;
            font-weight: 700;
        }

        .mobile-menu {
            display: none;
        }

        .mobile-links {
            background: var(--cream);
        }

        /* Hero */

        .hero {
            position: relative;
            min-height: 710px;
            min-height: min(850px, 88svh);
            isolation: isolate;
            display: flex;
            align-items: center;
            overflow: hidden;
            background:
                radial-gradient(
                    ellipse at 85% 30%,
                    #78937b70,
                    transparent 48%
                ),
                linear-gradient(120deg, #14362c, #56715b);
            color: #ffffff;
        }

        .hero-photo {
            position: absolute;
            z-index: -3;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            animation: scene-in 1.8s var(--ease) both;
        }

        .hero::after {
            content: '';
            position: absolute;
            z-index: -2;
            inset: 0;
            background:
                linear-gradient(
                    90deg,
                    #09251ed9,
                    #09251e65 66%,
                    #09251e25
                ),
                linear-gradient(0deg, #09251e7a, transparent 40%);
        }

        .hero::before {
            content: '';
            position: absolute;
            z-index: -1;
            inset: 22px;
            border: 1px solid #ffffff24;
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            padding: 85px 0 130px;
        }

        .hero .eyebrow {
            color: #e9d0a8;
            animation: rise-in .9s .1s var(--ease) both;
        }

        .hero h1 {
            max-width: 860px;
            font-size: clamp(68px, 7.5vw, 112px);
            line-height: .99;
            letter-spacing: -4.5px;
            margin-bottom: 28px;
            animation: rise-in 1s .2s var(--ease) both;
        }

        .hero h1 em {
            font-weight: 400;
            color: #e5c895;
        }

        .hero-description {
            max-width: 400px;
            color: #e3e8dc;
            font-size: 14px;
            line-height: 1.9;
            margin-bottom: 30px;
            animation: rise-in 1s .3s var(--ease) both;
        }

        .hero-content > .actions {
            animation: rise-in 1s .4s var(--ease) both;
        }

        .hero-note {
            font-size: 10px;
            color: #d9e1d4;
            margin: 21px 0 0;
            max-width: 390px;
            animation: rise-in 1s .5s var(--ease) both;
        }

        .hero-stamp {
            position: absolute;
            right: 0;
            top: 24%;
            width: 138px;
            height: 138px;
            border: 1px solid #ffffff70;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            transform: rotate(9deg);
            background: #173c3210;
            backdrop-filter: blur(3px);
        }

        .hero-stamp span {
            font-size: 8px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .hero-stamp b {
            font: italic 28px/1.4 Georgia, serif;
            font-weight: 400;
        }

        .hero-stamp i {
            font-style: normal;
            font-size: 19px;
            color: var(--gold);
        }

        .hero-bottom {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 30px;
        }

        .hero-bottom-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            font-size: 9px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .hero-bottom-inner a {
            display: flex;
            align-items: center;
            gap: 14px;
            min-height: 44px;
        }

        .scroll-arrow {
            width: 32px;
            height: 42px;
            border: 1px solid #ffffff55;
            border-radius: 22px;
            display: grid;
            place-items: center;
            font-size: 17px;
        }

        .hero-index {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .hero-index::before {
            content: '';
            display: block;
            width: 76px;
            height: 1px;
            background: #ffffff60;
        }

        /* Introduction */

        .intro-strip {
            position: relative;
            background: #e9ecdf;
            border-bottom: 1px solid var(--line);
        }

        .intro-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .intro-item {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 30px 24px;
        }

        .intro-item:first-child {
            padding-left: 0;
        }

        .intro-item + .intro-item {
            border-left: 1px solid #ccd5c3;
        }

        .intro-number {
            font: italic 30px Georgia, serif;
            color: #849170;
        }

        .intro-item strong {
            display: block;
            font-size: 12px;
            font-weight: 600;
        }

        .intro-item small {
            font-size: 11px;
            color: var(--muted);
        }

        .section-heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 36px;
            margin-bottom: 44px;
        }

        .section-heading h2 {
            margin: 0;
            font-size: clamp(39px, 4.4vw, 64px);
            letter-spacing: -2px;
        }

        .section-heading .eyebrow {
            color: #718157;
        }

        .section-description {
            max-width: 320px;
            margin: 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.85;
        }

        /* Rooms */

        .room-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 26px;
        }

        .room-card {
            min-width: 0;
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            overflow: hidden;
            transition:
                transform .5s var(--ease),
                box-shadow .5s var(--ease);
        }

        .room-card:hover {
            transform: translateY(-7px);
            box-shadow: 0 22px 40px #1a3b2912;
        }

        .room-media {
            position: relative;
            aspect-ratio: 4 / 3;
            overflow: hidden;
            background: #dce3d5;
        }

        .room-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .8s var(--ease);
        }

        .room-card:hover .room-image {
            transform: scale(1.06);
        }

        .room-placeholder {
            width: 100%;
            height: 100%;
            display: grid;
            place-items: center;
            background:
                radial-gradient(
                    ellipse at 30% 20%,
                    #d7dfcb,
                    transparent
                ),
                linear-gradient(135deg, #bfcfb5, #78927b);
            color: #304f3b;
            font: italic 24px Georgia, serif;
        }

        .room-type {
            position: absolute;
            top: 18px;
            left: 18px;
            max-width: calc(100% - 36px);
            padding: 7px 12px;
            border-radius: 4px;
            background: #f7f5eff2;
            color: var(--forest);
            font-size: 9px;
            letter-spacing: 1px;
            text-transform: uppercase;
            backdrop-filter: blur(8px);
        }

        .room-body {
            padding: 25px;
        }

        .room-number {
            margin-bottom: 9px;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1.8px;
            color: var(--muted);
        }

        .room-body h3 {
            font: 400 28px/1.2 Georgia, serif;
            letter-spacing: -.6px;
            margin-bottom: 23px;
            overflow-wrap: anywhere;
        }

        .room-price {
            display: flex;
            align-items: baseline;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 19px;
            border-top: 1px solid var(--line);
            padding-top: 18px;
        }

        .room-price strong {
            font-size: 22px;
            font-weight: 500;
            letter-spacing: -.7px;
        }

        .room-price span {
            font-size: 11px;
            color: var(--muted);
        }

        .room-price .currency {
            font-size: 10px;
            letter-spacing: .5px;
            color: var(--muted);
            margin-right: 3px;
        }

        .room-body .btn {
            width: 100%;
            justify-content: space-between;
            background: transparent;
            color: var(--forest);
            border-color: #b8c3b1;
        }

        .room-body .btn:hover {
            background: var(--forest);
            color: #ffffff;
        }

        .room-unavailable {
            display: grid;
            place-items: center;
            min-height: 50px;
            margin: 0;
            border-radius: 7px;
            background: #edf0e7;
            color: #6b7568;
            font-size: 12px;
        }

        .rooms-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            margin-top: 32px;
            flex-wrap: wrap;
        }

        .rooms-footer p {
            font-size: 11px;
            color: var(--muted);
            max-width: 590px;
            margin: 0;
        }

        .empty-state {
            padding: 35px;
            border: 1px solid var(--line);
            border-radius: 16px;
            background: var(--paper);
        }

        /* About */

        .about {
            position: relative;
            background: #e9ecdf;
        }

        .about-grid {
            display: grid;
            grid-template-columns: 1.05fr 1fr;
            gap: 100px;
            align-items: center;
        }

        .about-photo {
            position: relative;
            aspect-ratio: 5 / 6;
            max-height: 610px;
            border-radius: 150px 150px 12px 12px;
            overflow: hidden;
            background:
                radial-gradient(
                    ellipse at 70% 30%,
                    #a9b99a,
                    transparent
                ),
                linear-gradient(135deg, #789477, #254b3a);
        }

        .about-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .about-caption {
            position: absolute;
            bottom: 22px;
            left: 22px;
            right: 22px;
            padding: 20px 24px;
            background: #f7f5efed;
            border: 1px solid #ffffff70;
            border-radius: 6px;
            font: italic 22px/1.3 Georgia, serif;
            backdrop-filter: blur(12px);
        }

        .about-copy > .eyebrow {
            color: #6c7b52;
        }

        .about-copy h2 {
            font-size: clamp(38px, 4.4vw, 64px);
            letter-spacing: -2px;
            margin-bottom: 25px;
        }

        .about-copy > p {
            font-size: 13px;
            line-height: 1.95;
            color: #66745c;
        }

        .about-list {
            list-style: none;
            padding: 0;
            margin: 30px 0;
        }

        .about-list li {
            position: relative;
            padding: 17px 0 17px 28px;
            border-top: 1px solid #cad2bf;
        }

        .about-list li::before {
            content: '↗';
            position: absolute;
            left: 0;
            top: 14px;
            color: #7a875e;
            font-size: 17px;
        }

        .about-list strong {
            display: block;
            font-size: 12px;
            margin-bottom: 4px;
            font-weight: 600;
        }

        .about-list span {
            display: block;
            font-size: 12px;
            line-height: 1.75;
            color: #69745f;
        }

        /* Booking steps */

        .steps {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 40px;
        }

        .step {
            position: relative;
            padding: 0 25px 0 0;
            border-top: 1px solid #bfcab6;
        }

        .step-number {
            display: block;
            margin: 25px 0 32px;
            font: italic 42px Georgia, serif;
            color: #8d9c77;
        }

        .step h3 {
            font: 400 25px/1.25 Georgia, serif;
            letter-spacing: -.5px;
            margin-bottom: 15px;
        }

        .step p {
            font-size: 12px;
            line-height: 1.9;
            color: var(--muted);
            max-width: 300px;
            margin-bottom: 0;
        }

        .account-banner {
            position: relative;
            margin-top: 65px;
            padding: 40px 45px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 35px;
            background:
                radial-gradient(
                    ellipse at 100% 0,
                    #667a4e55,
                    transparent 60%
                ),
                var(--forest);
            color: #ffffff;
            overflow: hidden;
        }

        .account-banner h3 {
            font: 400 30px/1.2 Georgia, serif;
            letter-spacing: -.7px;
            margin-bottom: 10px;
        }

        .account-banner p {
            max-width: 550px;
            font-size: 12px;
            line-height: 1.8;
            color: #c8d5c1;
            margin: 0;
        }

        .account-banner .actions {
            flex-shrink: 0;
        }

        .account-banner .access-switch {
            background: #ffffff10;
            border-color: #ffffff60;
        }

        .account-banner .access-switch a {
            color: #ffffff;
            min-height: 38px;
            padding-inline: 18px;
        }

        .account-banner .access-switch a:first-child {
            background: var(--gold);
            color: var(--forest);
        }

        .account-banner .access-switch a:hover {
            background: #ffffff25;
            color: #ffffff;
        }

        /* Contact */

        .contact {
            background: #183a30;
            color: #ffffff;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1.15fr 1fr;
            gap: 120px;
        }

        .contact .eyebrow {
            color: #cbb584;
        }

        .contact h2 {
            font-size: clamp(44px, 5.1vw, 75px);
            letter-spacing: -2px;
        }

        .contact-intro {
            max-width: 390px;
            color: #c2cebd;
            font-size: 13px;
            line-height: 1.9;
            margin-bottom: 30px;
        }

        .contact-details {
            margin: 0;
        }

        .contact-details > div {
            padding: 23px 0;
            border-bottom: 1px solid #ffffff24;
        }

        .contact-details > div:first-child {
            padding-top: 0;
        }

        .contact-details dt {
            margin-bottom: 9px;
            color: #a5b49e;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .contact-details dd {
            margin: 0;
            font-size: 16px;
            overflow-wrap: anywhere;
        }

        .contact-details a:hover,
        .contact-social a:hover {
            color: var(--gold);
        }

        .contact-social {
            display: flex;
            gap: 24px;
            flex-wrap: wrap;
            margin-top: 28px;
            font-size: 11px;
        }

        /* Footer */

        .site-footer {
            background: var(--forest-dark);
            color: #b7c4ae;
            padding: 28px 0;
        }

        .footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            flex-wrap: wrap;
        }

        .footer-inner p {
            font-size: 10px;
            margin: 0;
        }

        .footer-links {
            display: flex;
            gap: 22px;
            flex-wrap: wrap;
            font-size: 10px;
        }

        .mobile-booking {
            display: none;
        }

        /* Motion */

        @keyframes rise-in {
            from {
                opacity: 0;
                transform: translateY(25px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scene-in {
            from {
                transform: scale(1.065);
            }

            to {
                transform: scale(1);
            }
        }

        /* Large screens */

        @media (min-width: 1600px) {
            .hero {
                min-height: 820px;
            }
        }

        /* Smaller laptops */

        @media (max-width: 1180px) {
            .container {
                width: calc(100% - 64px);
            }

            .desktop-nav {
                gap: 19px;
            }

            .access-caption {
                display: none;
            }

            .nav-tools {
                gap: 12px;
            }

            .nav > .desktop-nav {
                margin-left: auto;
            }

            .header-book {
                display: none;
            }

            .hero-stamp {
                right: 0;
                top: 25%;
                width: 120px;
                height: 120px;
            }

            .hero h1 {
                font-size: 90px;
                max-width: 690px;
            }

            .about-grid {
                gap: 60px;
            }

            .contact-grid {
                gap: 70px;
            }
        }

        /* Tablet */

        @media (max-width: 960px) {
            .container {
                width: calc(100% - 48px);
            }

            .nav {
                min-height: 82px;
                gap: 16px;
            }

            .desktop-nav {
                display: none;
            }

            .nav-tools {
                margin-left: auto;
            }

            .brand-name {
                font-size: 22px;
            }

            .brand-subtitle {
                font-size: 7px;
            }

            .mobile-menu {
                display: block;
                position: relative;
            }

            .mobile-menu summary {
                list-style: none;
                display: grid;
                place-items: center;
                min-width: 42px;
                min-height: 42px;
                border: 1px solid #bdc7b5;
                border-radius: 50%;
                cursor: pointer;
            }

            .mobile-menu summary::-webkit-details-marker {
                display: none;
            }

            .menu-lines,
            .menu-lines::before,
            .menu-lines::after {
                display: block;
                width: 16px;
                height: 1px;
                background: var(--forest);
                transition: transform .25s;
            }

            .menu-lines {
                position: relative;
            }

            .menu-lines::before,
            .menu-lines::after {
                content: '';
                position: absolute;
                left: 0;
            }

            .menu-lines::before {
                top: -5px;
            }

            .menu-lines::after {
                top: 5px;
            }

            .mobile-menu[open] .menu-lines {
                background: transparent;
            }

            .mobile-menu[open] .menu-lines::before {
                transform: translateY(5px) rotate(45deg);
            }

            .mobile-menu[open] .menu-lines::after {
                transform: translateY(-5px) rotate(-45deg);
            }

            .mobile-links {
                position: absolute;
                right: 0;
                top: calc(100% + 20px);
                width: min(340px, calc(100vw - 48px));
                max-height: calc(100svh - 150px);
                overflow-y: auto;
                display: grid;
                gap: 4px;
                padding: 18px;
                border: 1px solid var(--line);
                border-radius: 14px;
                box-shadow: 0 20px 45px #112d2626;
            }

            .mobile-links > a:not(.btn) {
                padding: 12px 10px;
                font-size: 14px;
                min-height: 44px;
            }

            .mobile-links hr {
                border: 0;
                border-top: 1px solid var(--line);
                width: 100%;
            }

            .mobile-links .logout-button {
                width: 100%;
                text-align: left;
            }

            .mobile-menu[open] .mobile-links {
                animation: rise-in .25s var(--ease);
            }

            .hero {
                min-height: 690px;
            }

            .hero h1 {
                font-size: clamp(64px, 9.5vw, 88px);
            }

            .hero-stamp {
                top: 53%;
                width: 105px;
                height: 105px;
            }

            .hero-stamp b {
                font-size: 24px;
            }

            .intro-item {
                padding: 25px 16px;
                gap: 12px;
            }

            .intro-item small {
                font-size: 10px;
            }

            .section {
                padding: 85px 0;
            }

            .room-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 20px;
            }

            .about-grid {
                gap: 38px;
            }

            .about-photo {
                aspect-ratio: 4 / 6;
            }

            .about-copy h2 {
                font-size: 43px;
            }

            .about-list {
                margin: 23px 0;
            }

            .steps {
                gap: 25px;
            }

            .step {
                padding-right: 0;
            }

            .account-banner {
                padding: 32px;
                align-items: flex-start;
                flex-direction: column;
            }

            .contact-grid {
                gap: 45px;
            }

            .contact h2 {
                font-size: 52px;
            }

            .account-link {
                max-width: 110px;
                font-size: 11px;
            }

            .nav-tools > .logout-form {
                display: none;
            }
        }

        /* Mobile */

        @media (max-width: 640px) {
            html {
                scroll-padding-top: 90px;
            }

            .container {
                width: calc(100% - 36px);
            }

            .nav {
                min-height: 76px;
                gap: 10px;
            }

            .brand {
                gap: 8px;
            }

            .brand-logo,
            .brand-mark {
                width: 32px;
                height: 32px;
            }

            .brand-mark {
                font-size: 20px;
            }

            .brand-name {
                font-size: 18px;
                letter-spacing: -.6px;
            }

            .brand-subtitle {
                font-size: 6px;
                letter-spacing: 1.15px;
                margin-top: 4px;
            }

            .nav-tools {
                gap: 8px;
            }

            .access-switch {
                padding: 3px;
            }

            .access-switch a {
                min-width: 44px;
                min-height: 44px;
                padding: 5px 8px;
                font-size: 10px;
            }

            .access-divider {
                font-size: 10px;
                padding: 0 1px;
            }

            .mobile-menu summary {
                min-width: 44px;
                min-height: 44px;
            }

            .hero {
                min-height: 640px;
            }

            .hero::before {
                inset: 12px;
            }

            .hero::after {
                background:
                    linear-gradient(90deg, #09251ed9, #09251e85),
                    linear-gradient(0deg, #09251e4d, transparent);
            }

            .hero-content {
                padding: 65px 0 112px;
            }

            .hero .eyebrow {
                font-size: 8px;
                letter-spacing: 2px;
            }

            .hero h1 {
                font-size: clamp(52px, 13.8vw, 78px);
                letter-spacing: -2.7px;
                max-width: 100%;
                margin-bottom: 25px;
            }

            .hero-description {
                max-width: 300px;
                font-size: 12px;
            }

            .hero-content > .actions {
                gap: 9px;
            }

            .hero-content .btn {
                padding: 12px 17px;
                gap: 15px;
                font-size: 11px;
            }

            .hero-note {
                font-size: 9px;
                max-width: 280px;
            }

            .hero-stamp {
                display: none;
            }

            .hero-bottom {
                bottom: 24px;
            }

            .hero-bottom-inner {
                font-size: 8px;
                letter-spacing: 1px;
            }

            .hero-index {
                display: none;
            }

            .scroll-arrow {
                width: 27px;
                height: 35px;
                font-size: 14px;
            }

            .hero-bottom-inner a {
                gap: 8px;
            }

            .intro-grid {
                grid-template-columns: 1fr;
            }

            .intro-item,
            .intro-item:first-child {
                padding: 18px 0;
                gap: 18px;
            }

            .intro-item + .intro-item {
                border-left: 0;
                border-top: 1px solid #cdd5c3;
            }

            .intro-number {
                font-size: 24px;
            }

            .intro-item small {
                font-size: 11px;
            }

            .section {
                padding: 66px 0;
            }

            .section-heading {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
                margin-bottom: 29px;
            }

            .section-heading h2 {
                font-size: 43px;
                letter-spacing: -1.8px;
            }

            .eyebrow {
                font-size: 8px;
                letter-spacing: 2px;
                margin-bottom: 16px;
            }

            .section-description {
                font-size: 12px;
                max-width: 320px;
            }

            .room-grid,
            .about-grid,
            .steps,
            .contact-grid {
                grid-template-columns: 1fr;
            }

            .room-grid {
                gap: 23px;
            }

            .room-media {
                aspect-ratio: 16 / 11;
            }

            .room-body {
                padding: 23px;
            }

            .room-body h3 {
                font-size: 29px;
            }

            .rooms-footer {
                align-items: stretch;
                gap: 18px;
            }

            .rooms-footer .btn {
                width: 100%;
            }

            .about-grid {
                gap: 35px;
            }

            .about-photo {
                aspect-ratio: 5 / 5.7;
                max-height: 470px;
                border-radius: 130px 130px 10px 10px;
            }

            .about-caption {
                font-size: 21px;
                padding: 17px;
                bottom: 17px;
                left: 17px;
                right: 17px;
            }

            .about-copy h2 {
                font-size: 45px;
            }

            .about-copy > p {
                font-size: 12px;
            }

            .steps {
                gap: 33px;
            }

            .step-number {
                margin: 17px 0;
                font-size: 35px;
            }

            .step p {
                max-width: none;
            }

            .account-banner {
                padding: 28px 23px;
                margin-top: 40px;
                gap: 23px;
            }

            .account-banner h3 {
                font-size: 28px;
            }

            .account-banner .actions {
                width: 100%;
            }

            .account-banner .btn {
                padding-inline: 16px;
                gap: 12px;
            }

            .account-banner .access-switch a {
                padding-inline: 12px;
            }

            .contact-grid {
                gap: 42px;
            }

            .contact h2 {
                font-size: 49px;
                letter-spacing: -1.9px;
            }

            .contact-intro {
                font-size: 12px;
            }

            .contact-details dd {
                font-size: 15px;
            }

            .footer-inner {
                gap: 18px;
            }

            .footer-links {
                gap: 19px;
            }

            .site-footer {
                padding-bottom: calc(
                    110px + env(safe-area-inset-bottom)
                );
            }

            .mobile-booking {
                position: fixed;
                z-index: 80;
                left: 12px;
                right: 12px;
                bottom: calc(12px + env(safe-area-inset-bottom));
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                padding: 11px 13px;
                border: 1px solid #d5ddca;
                border-radius: 12px;
                background: #f7f5eff5;
                box-shadow: 0 8px 35px #102c2426;
                backdrop-filter: blur(14px);
            }

            .mobile-booking strong {
                display: block;
                font-size: 11px;
            }

            .mobile-booking small {
                display: block;
                font-size: 9px;
                color: var(--muted);
            }

            .mobile-booking .btn {
                min-height: 43px;
                padding: 10px 15px;
                font-size: 11px;
            }

            .account-link {
                max-width: 80px;
                font-size: 10px;
            }

            .mobile-links {
                width: min(340px, calc(100vw - 36px));
            }
        }

        @media (max-width: 370px) {
            .nav,
            .nav-tools {
                gap: 6px;
            }

            .brand-logo,
            .brand-mark,
            .brand-subtitle {
                display: none;
            }

            .brand-name {
                font-size: 18px;
            }

            .hero h1 {
                font-size: 49px;
            }

            .hero-content .btn {
                padding-inline: 13px;
            }

            .mobile-booking strong {
                font-size: 10px;
            }

            .mobile-booking .btn {
                padding-inline: 12px;
            }

            .footer-links {
                font-size: 9px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            *,
            *::before,
            *::after {
                animation: none !important;
                transition: none !important;
            }

            .btn:hover,
            .room-card:hover,
            .room-card:hover .room-image {
                transform: none;
            }
        }
    </style>
</head>

<body>
    <a href="#main-content" class="skip-link">
        Skip to content
    </a>

    <header class="site-header">
        <div class="container nav">
            <a
                href="{{ route('welcome') }}"
                class="brand"
                aria-label="McLovin Resort home"
            >
                @if(is_file(public_path('images/logo.jpg')))
                    <img
                        src="{{ $logo }}"
                        alt=""
                        width="44"
                        height="44"
                        class="brand-logo"
                    >
                @else
                    <span class="brand-mark" aria-hidden="true">M</span>
                @endif

                <span>
                    <span class="brand-name">McLovin Resort</span>
                    <span class="brand-subtitle">
                        A little closer to nature
                    </span>
                </span>
            </a>

            <nav class="desktop-nav" aria-label="Main navigation">
                <a href="#rooms" class="nav-link">Our rooms</a>
                <a href="#about" class="nav-link">The resort</a>
                <a href="#how-it-works" class="nav-link">Your stay</a>
                <a href="#contact" class="nav-link">Contact</a>
            </nav>

            <div class="nav-tools">
                @if($isGuest)
                    <a
                        href="{{ route('guest.dashboard') }}"
                        class="account-link"
                    >
                        My Bookings ↗
                    </a>

                    <form
                        method="POST"
                        action="{{ route('guest.logout') }}"
                        class="logout-form"
                    >
                        @csrf

                        <button type="submit" class="logout-button">
                            Sign out
                        </button>
                    </form>
                @elseif($isStaff)
                    <a
                        href="{{ route('dashboard') }}"
                        class="account-link"
                    >
                        Staff Dashboard ↗
                    </a>

                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                        class="logout-form"
                    >
                        @csrf

                        <button type="submit" class="logout-button">
                            Sign out
                        </button>
                    </form>
                @elseif(!$currentUser)
                    <div class="access-wrap">
                        <span class="access-caption">Login</span>

                        <div
                            class="access-switch"
                            role="group"
                            aria-label="Guest or staff login"
                        >
                            <a
                                href="{{ route('guest.login') }}"
                                aria-label="Guest login"
                            >
                                Guest
                            </a>

                            <span
                                class="access-divider"
                                aria-hidden="true"
                            >
                                /
                            </span>

                            <a
                                href="{{ route('login') }}"
                                aria-label="Staff login"
                            >
                                Staff
                            </a>
                        </div>
                    </div>
                @endif

                <a
                    href="{{ route('public.book') }}"
                    class="btn btn-green header-book"
                >
                    Book a stay
                    <span aria-hidden="true">↗</span>
                </a>

                <details class="mobile-menu" id="mobileMenu">
                    <summary aria-label="Navigation menu">
                        <span class="menu-lines" aria-hidden="true"></span>
                    </summary>

                    <nav
                        class="mobile-links"
                        aria-label="Mobile navigation"
                    >
                        <a href="#rooms">Our rooms</a>
                        <a href="#about">The resort</a>
                        <a href="#how-it-works">Your stay</a>
                        <a href="#contact">Contact</a>

                        <hr>

                        @if($isGuest)
                            <a href="{{ route('guest.dashboard') }}">
                                My Bookings
                            </a>

                            <form
                                method="POST"
                                action="{{ route('guest.logout') }}"
                                class="logout-form"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="logout-button"
                                >
                                    Sign out
                                </button>
                            </form>
                        @elseif($isStaff)
                            <a href="{{ route('dashboard') }}">
                                Staff Dashboard
                            </a>

                            <form
                                method="POST"
                                action="{{ route('logout') }}"
                                class="logout-form"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="logout-button"
                                >
                                    Sign out
                                </button>
                            </form>
                        @elseif(!$currentUser)
                            <a href="{{ route('guest.register') }}">
                                Create guest account ↗
                            </a>
                        @endif

                        <a
                            href="{{ route('public.book') }}"
                            class="btn btn-green"
                        >
                            Book a stay
                            <span aria-hidden="true">↗</span>
                        </a>
                    </nav>
                </details>
            </div>
        </div>

        <div class="scroll-progress" aria-hidden="true"></div>
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
                <span class="eyebrow">
                    Rangamati, Bangladesh · A quieter escape
                </span>

                <h1 id="hero-title">
                    Less rush.<br>
                    <em>More living.</em>
                </h1>

                <p class="hero-description">
                    A little room to breathe. A little time for yourself.
                    Find your next stay, surrounded by a slower pace
                    at McLovin Resort.
                </p>

                <div class="actions">
                    <a
                        href="{{ route('public.book') }}"
                        class="btn btn-gold"
                    >
                        Plan your stay
                        <span aria-hidden="true">↗</span>
                    </a>

                    <a href="#rooms" class="btn btn-white-outline">
                        Explore rooms
                    </a>
                </div>

                <p class="hero-note">
                    Book directly. Your reservation is confirmed after
                    our team reviews payment.
                </p>

                <div class="hero-stamp" aria-hidden="true">
                    <span>Take a breath</span>
                    <b>Stay a little.</b>
                    <i>✳</i>
                    <span>McLovin Resort</span>
                </div>
            </div>

            <div class="hero-bottom">
                <div class="container hero-bottom-inner">
                    <span>Room for a different kind of day.</span>

                    <span class="hero-index" aria-hidden="true">
                        01 / The escape
                    </span>

                    <a href="#rooms">
                        Explore below
                        <span class="scroll-arrow" aria-hidden="true">
                            ↓
                        </span>
                    </a>
                </div>
            </div>
        </section>

        <div class="intro-strip">
            <div class="container intro-grid">
                <div class="intro-item">
                    <span class="intro-number" aria-hidden="true">
                        01
                    </span>

                    <div>
                        <strong>Find your room</strong>
                        <small>Explore rooms and nightly prices</small>
                    </div>
                </div>

                <div class="intro-item">
                    <span class="intro-number" aria-hidden="true">
                        02
                    </span>

                    <div>
                        <strong>Choose your dates</strong>
                        <small>Availability checked when you book</small>
                    </div>
                </div>

                <div class="intro-item">
                    <span class="intro-number" aria-hidden="true">
                        03
                    </span>

                    <div>
                        <strong>Keep track of your stay</strong>
                        <small>View account bookings in one place</small>
                    </div>
                </div>
            </div>
        </div>

        <section
            class="section"
            id="rooms"
            aria-labelledby="rooms-title"
        >
            <div class="container">
                <div class="section-heading">
                    <div>
                        <span class="eyebrow">
                            Your own space to unwind
                        </span>

                        <h2 id="rooms-title">
                            Somewhere to<br>
                            <em>call your own.</em>
                        </h2>
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
                                                <span class="currency">
                                                    BDT
                                                </span>

                                                {{ number_format((float) $room->price, 2) }}
                                            </strong>

                                            <span>/ night</span>
                                        @else
                                            <span>
                                                Contact us for pricing
                                            </span>
                                        @endif
                                    </div>

                                    @if($acceptsBookings && $hasPrice)
                                        <a
                                            href="{{ route('public.book', ['room' => $room->id]) }}"
                                            class="btn btn-green"
                                            aria-label="Choose dates for {{ $room->name }}"
                                        >
                                            Choose dates
                                            <span aria-hidden="true">
                                                ↗
                                            </span>
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
                        Choose your dates to check availability and
                        complete your reservation.
                    </p>

                    <a
                        href="{{ route('public.book') }}"
                        class="btn btn-outline"
                    >
                        Open booking form
                        <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>
        </section>

        <section
            class="section about"
            id="about"
            aria-labelledby="about-title"
        >
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
                        A little closer<br>
                        to <em>what matters.</em>
                    </h2>

                    <p>
                        A good getaway starts with a little breathing
                        space. Whether you are planning time with
                        family, a trip together, or a break from your
                        routine, start by finding a room that suits
                        your stay.
                    </p>

                    <ul class="about-list">
                        <li>
                            <strong>A simple way to plan</strong>

                            <span>
                                Choose your room and dates through
                                our direct booking form.
                            </span>
                        </li>

                        <li>
                            <strong>
                                Your reservation, easy to find
                            </strong>

                            <span>
                                Sign in before booking to keep your
                                new reservations in your guest account.
                            </span>
                        </li>

                        <li>
                            <strong>A team you can contact</strong>

                            <span>
                                Ask us about your visit, payment
                                review or booking details.
                            </span>
                        </li>
                    </ul>

                    <a href="#contact" class="btn btn-green">
                        Talk to our team
                        <span aria-hidden="true">↗</span>
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
                        <span class="eyebrow">
                            From planning to arriving
                        </span>

                        <h2 id="steps-title">
                            A good stay.<br>
                            <em>A simple beginning.</em>
                        </h2>
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
                            your receipt. A submission is awaiting
                            review, not yet a verified payment.
                        </p>
                    </article>

                    <article class="step">
                        <span class="step-number">03</span>

                        <h3>Look for confirmation</h3>

                        <p>
                            Our team reviews payment and confirms
                            your reservation. Account guests can
                            check the status in My Bookings.
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

                        <a
                            href="{{ route('guest.dashboard') }}"
                            class="btn btn-gold"
                        >
                            My Bookings
                            <span aria-hidden="true">↗</span>
                        </a>
                    @elseif($isStaff)
                        <div>
                            <h3>Welcome back to McLovin.</h3>

                            <p>
                                Open your staff dashboard to manage
                                the resort’s day-to-day activity.
                            </p>
                        </div>

                        <a
                            href="{{ route('dashboard') }}"
                            class="btn btn-gold"
                        >
                            Staff Dashboard
                            <span aria-hidden="true">↗</span>
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
                            <a
                                href="{{ route('guest.register') }}"
                                class="btn btn-gold"
                            >
                                Create Account
                            </a>

                            <div
                                class="access-switch"
                                role="group"
                                aria-label="Guest or staff login"
                            >
                                <a
                                    href="{{ route('guest.login') }}"
                                    aria-label="Guest login"
                                >
                                    Guest
                                </a>

                                <span
                                    class="access-divider"
                                    aria-hidden="true"
                                >
                                    /
                                </span>

                                <a
                                    href="{{ route('login') }}"
                                    aria-label="Staff login"
                                >
                                    Staff
                                </a>
                            </div>
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
                    <span class="eyebrow">
                        Let’s plan something good
                    </span>

                    <h2 id="contact-title">
                        Your getaway.<br>
                        Our warm welcome.
                    </h2>

                    <p class="contact-intro">
                        Have a question before booking? Get in touch
                        for help with room options, your reservation
                        or planning your visit.
                    </p>

                    <div class="actions">
                        <a
                            href="tel:{{ $phone }}"
                            class="btn btn-gold"
                        >
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
                            Facebook
                            <span aria-hidden="true">↗</span>
                        </a>

                        <a href="{{ route('public.book') }}">
                            Booking form
                            <span aria-hidden="true">↗</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
            <p>
                © {{ date('Y') }} McLovin Resort.
                All rights reserved.
            </p>

            <nav class="footer-links" aria-label="Footer navigation">
                <a href="#rooms">Rooms</a>
                <a href="#contact">Contact</a>

                @if(!$currentUser)
                    <a href="{{ route('guest.login') }}">
                        Guest Login
                    </a>

                    <a href="{{ route('login') }}">
                        Staff Login
                    </a>
                @elseif($isStaff)
                    <a href="{{ route('dashboard') }}">
                        Staff Dashboard
                    </a>
                @elseif($isGuest)
                    <a href="{{ route('guest.dashboard') }}">
                        My Bookings
                    </a>
                @endif
            </nav>
        </div>
    </footer>

    <div class="mobile-booking" aria-label="Quick booking">
        <div>
            <strong>A stay worth slowing down for.</strong>
            <small>Choose your room and dates</small>
        </div>

        <a
            href="{{ route('public.book') }}"
            class="btn btn-green"
        >
            Book a stay
        </a>
    </div>

    <script>
        (() => {
            const header = document.querySelector('.site-header');
            const progress = document.querySelector('.scroll-progress');
            const menu = document.getElementById('mobileMenu');

            const reduceMotion = window.matchMedia(
                '(prefers-reduced-motion: reduce)'
            );

            const desktop = window.matchMedia(
                '(min-width: 961px)'
            );

            let scheduled = false;

            function updateHeader() {
                const offset = window.scrollY;

                const distance =
                    document.documentElement.scrollHeight -
                    window.innerHeight;

                header?.classList.toggle('scrolled', offset > 20);

                if (progress) {
                    const ratio = distance > 0
                        ? Math.min(1, Math.max(0, offset / distance))
                        : 0;

                    progress.style.transform = `scaleX(${ratio})`;
                }

                scheduled = false;
            }

            window.addEventListener('scroll', () => {
                if (!scheduled) {
                    scheduled = true;
                    window.requestAnimationFrame(updateHeader);
                }
            }, { passive: true });

            window.addEventListener('resize', updateHeader);
            window.addEventListener('load', updateHeader);

            updateHeader();

            function closeMenu(restoreFocus = false) {
                if (!menu?.open) {
                    return;
                }

                menu.open = false;

                if (restoreFocus) {
                    menu.querySelector('summary')?.focus();
                }
            }

            menu?.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', () => {
                    if (
                        link.hash &&
                        link.pathname === location.pathname
                    ) {
                        const destination = document.getElementById(
                            link.hash.slice(1)
                        );

                        if (destination) {
                            destination.setAttribute('tabindex', '-1');

                            destination.focus({
                                preventScroll: true
                            });

                            destination.addEventListener('blur', () => {
                                destination.removeAttribute('tabindex');
                            }, { once: true });
                        }
                    }

                    closeMenu();
                });
            });

            document.addEventListener('click', (event) => {
                if (menu && !menu.contains(event.target)) {
                    closeMenu();
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && menu?.open) {
                    event.preventDefault();
                    closeMenu(true);
                }
            });

            function viewportChange() {
                if (desktop.matches) {
                    closeMenu();
                }
            }

            if (desktop.addEventListener) {
                desktop.addEventListener('change', viewportChange);
            } else {
                desktop.addListener(viewportChange);
            }

            window.addEventListener('pageshow', () => {
                closeMenu();
            });

            const animations = new Set();
            let observer;

            if (
                !reduceMotion.matches &&
                'IntersectionObserver' in window &&
                'animate' in Element.prototype
            ) {
                const targets = document.querySelectorAll(
                    '.intro-item, .section-heading, .room-card, ' +
                    '.about-photo, .about-copy, .step, ' +
                    '.account-banner, .contact-grid > div'
                );

                observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (!entry.isIntersecting) {
                            return;
                        }

                        observer.unobserve(entry.target);

                        if (reduceMotion.matches) {
                            return;
                        }

                        const siblings = Array.from(
                            entry.target.parentElement.children
                        );

                        const delay = Math.min(
                            siblings.indexOf(entry.target) * 65,
                            195
                        );

                        const animation = entry.target.animate([
                            {
                                opacity: 0,
                                transform: 'translateY(24px)'
                            },
                            {
                                opacity: 1,
                                transform: 'translateY(0)'
                            }
                        ], {
                            duration: 650,
                            delay,
                            easing: 'cubic-bezier(.22,1,.36,1)',
                            fill: 'backwards'
                        });

                        animations.add(animation);

                        animation.finished
                            .then(() => animations.delete(animation))
                            .catch(() => animations.delete(animation));
                    });
                }, {
                    threshold: 0.08
                });

                targets.forEach((target) => {
                    observer.observe(target);
                });
            }

            function motionPreferenceChanged() {
                if (reduceMotion.matches) {
                    observer?.disconnect();

                    animations.forEach((animation) => {
                        animation.cancel();
                    });

                    animations.clear();
                }
            }

            if (reduceMotion.addEventListener) {
                reduceMotion.addEventListener(
                    'change',
                    motionPreferenceChanged
                );
            } else {
                reduceMotion.addListener(motionPreferenceChanged);
            }
        })();
    </script>
</body>
</html>