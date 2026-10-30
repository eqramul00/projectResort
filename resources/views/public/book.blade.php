@php
    $currentUser = auth()->user();

    $isGuest = $currentUser && $currentUser->isGuest();
    $isStaff = $currentUser && $currentUser->canViewBookings();

    $logoExists = is_file(public_path('images/logo.jpg'));
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, viewport-fit=cover"
    >

    <meta name="theme-color" content="#193d32">

    <meta
        name="description"
        content="Explore rooms at McLovin Resort, view room photos and choose a room for your next stay."
    >

    <title>Find Your Stay | McLovin Resort</title>

    <style>
        :root {
            --forest: #193d32;
            --dark: #112d26;
            --cream: #f7f5ef;
            --paper: #fffefa;
            --ink: #243e34;
            --muted: #68776c;
            --gold: #e5bd7c;
            --line: #d9ded4;
            --sage: #e9ecdf;
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
            -webkit-tap-highlight-color: transparent;
        }

        button {
            font: inherit;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
        }

        img {
            display: block;
            max-width: 100%;
        }

        [hidden] {
            display: none !important;
        }

        h1,
        h2,
        h3,
        p {
            margin-top: 0;
        }

        ::selection {
            background: var(--gold);
            color: var(--dark);
        }

        :focus-visible {
            outline: 3px solid #b56c25;
            outline-offset: 5px;
        }

        .container {
            width: min(1280px, calc(100% - 96px));
            margin-inline: auto;
        }

        .visually-hidden {
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

        .skip-link {
            position: fixed;
            top: -100px;
            left: 20px;
            z-index: 500;
            padding: 12px 20px;
            background: #ffffff;
            color: var(--forest);
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
            box-shadow: 0 6px 24px #112d260d;
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
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px;
            min-height: 92px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .brand-logo,
        .brand-mark {
            width: 44px;
            height: 44px;
            border-radius: 50%;
        }

        .brand-logo {
            object-fit: contain;
            background: #ffffff;
        }

        .brand-mark {
            display: grid;
            place-items: center;
            border: 1px solid #9ba88b;
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
            color: var(--muted);
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 2.3px;
            text-transform: uppercase;
        }

        .desktop-nav,
        .nav-tools {
            display: flex;
            align-items: center;
        }

        .desktop-nav {
            gap: 28px;
            margin-left: auto;
            margin-right: 20px;
        }

        .nav-tools {
            gap: 16px;
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
            background: currentColor;
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .35s var(--ease);
        }

        .nav-link:hover::after {
            transform: scaleX(1);
        }

        .access-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .access-caption {
            color: var(--muted);
            font-size: 9px;
            letter-spacing: 1.4px;
            text-transform: uppercase;
        }

        .access-switch {
            display: inline-flex;
            align-items: center;
            padding: 4px;
            border: 1px solid #bac4b6;
            border-radius: 30px;
            background: #eeeee5;
            white-space: nowrap;
        }

        .access-switch a {
            display: grid;
            place-items: center;
            min-width: 59px;
            min-height: 36px;
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

        .account-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 10px 16px;
            border: 1px solid #bdc8b5;
            border-radius: 25px;
            background: #e9ede2;
            font-size: 11px;
            font-weight: 700;
        }

        .mobile-menu {
            display: none;
            position: relative;
        }

        .mobile-menu summary {
            display: grid;
            place-items: center;
            width: 44px;
            height: 44px;
            border: 1px solid #bdc7b5;
            border-radius: 50%;
            list-style: none;
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
            top: calc(100% + 20px);
            right: 0;
            display: grid;
            gap: 5px;
            width: min(320px, calc(100vw - 36px));
            padding: 17px;
            border: 1px solid var(--line);
            border-radius: 14px;
            background: var(--cream);
            box-shadow: 0 20px 45px #112d2626;
        }

        .mobile-links a {
            display: flex;
            align-items: center;
            min-height: 46px;
            padding: 10px 12px;
            border-radius: 7px;
            font-size: 13px;
        }

        .mobile-links a:hover {
            background: var(--sage);
        }

        .mobile-menu[open] .mobile-links {
            animation: rise-in .3s var(--ease);
        }

        /* Page introduction */

        .intro-section {
            position: relative;
            overflow: hidden;
            border-bottom: 1px solid var(--line);
            background:
                radial-gradient(
                    ellipse at 100% 0,
                    #dce4cf9c,
                    transparent 60%
                ),
                var(--cream);
        }

        .intro-section::after {
            content: '';
            position: absolute;
            top: -180px;
            right: -180px;
            width: 540px;
            height: 540px;
            border: 1px solid #bbc8ac70;
            border-radius: 50%;
            pointer-events: none;
        }

        .breadcrumbs {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            padding-top: 32px;
            font-size: 10px;
            color: var(--muted);
        }

        .breadcrumbs a:hover {
            color: var(--forest);
        }

        .breadcrumbs [aria-current] {
            color: var(--forest);
        }

        .intro {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 45px;
            padding: 48px 0 62px;
        }

        .eyebrow {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 22px;
            color: #718157;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 2.5px;
            text-transform: uppercase;
        }

        .eyebrow::before {
            content: '';
            width: 27px;
            height: 1px;
            background: currentColor;
        }

        .intro .eyebrow {
            animation: rise-in .8s .05s var(--ease) both;
        }

        h1 {
            margin: 0 0 24px;
            font: 400 clamp(48px, 5.8vw, 82px)/1.03 Georgia, serif;
            letter-spacing: -3.3px;
            animation: rise-in .9s .12s var(--ease) both;
        }

        h1 em {
            color: #778760;
            font-weight: 400;
        }

        .intro-description {
            max-width: 470px;
            margin: 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.9;
            animation: rise-in .9s .2s var(--ease) both;
        }

        .intro-note {
            width: 235px;
            flex-shrink: 0;
            padding-bottom: 6px;
            animation: rise-in .9s .25s var(--ease) both;
        }

        .intro-note-number {
            display: block;
            margin-bottom: 16px;
            color: #8a9876;
            font: italic 46px/1 Georgia, serif;
        }

        .intro-note strong {
            display: block;
            margin-bottom: 7px;
            font-size: 12px;
            font-weight: 600;
        }

        .intro-note p {
            margin: 0;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.8;
        }

        /* Booking progress */

        .journey-strip {
            border-bottom: 1px solid #d1dac7;
            background: var(--sage);
        }

        .journey {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .journey-step {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 23px 26px;
        }

        .journey-step:first-child {
            padding-left: 0;
        }

        .journey-step + .journey-step {
            border-left: 1px solid #cdd6c1;
        }

        .journey-number {
            display: grid;
            place-items: center;
            width: 32px;
            height: 32px;
            flex-shrink: 0;
            border: 1px solid #aebba0;
            border-radius: 50%;
            color: #6b7b58;
            font-size: 11px;
        }

        .journey-step.is-current .journey-number {
            border-color: var(--forest);
            background: var(--forest);
            color: #ffffff;
        }

        .journey-step strong {
            display: block;
            font-size: 11px;
            font-weight: 600;
        }

        .journey-step small {
            display: block;
            margin-top: 2px;
            color: var(--muted);
            font-size: 10px;
        }

        /* Room list */

        .rooms-section {
            padding: 54px 0 76px;
        }

        .collection-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 27px;
        }

        .collection-heading h2 {
            margin: 0;
            font: 400 27px/1.2 Georgia, serif;
            letter-spacing: -.7px;
        }

        .collection-meta {
            display: flex;
            align-items: center;
            gap: 9px;
            color: var(--muted);
            font-size: 10px;
        }

        .collection-meta::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #899973;
        }

        .rooms {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            align-items: start;
            gap: 30px;
        }

        .room-card {
            min-width: 0;
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: 18px;
            background: var(--paper);
            transition:
                transform .5s var(--ease),
                box-shadow .5s var(--ease);
        }

        .room-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 22px 45px #193d3212;
        }

        /* Room gallery */

        .gallery-main {
            position: relative;
            display: grid;
            place-items: center;
            aspect-ratio: 16 / 10;
            overflow: hidden;
            isolation: isolate;
            background: #dce3d5;
        }

        .gallery-main > img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .9s var(--ease);
        }

        .room-card:hover .gallery-main > img {
            transform: scale(1.035);
        }

        .gallery-main::after {
            content: '';
            position: absolute;
            z-index: 1;
            inset: 55% 0 0;
            background: linear-gradient(transparent, #102b2455);
            pointer-events: none;
        }

        .gallery-placeholder {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            background:
                radial-gradient(
                    ellipse at 30% 20%,
                    #dae3ce,
                    transparent
                ),
                linear-gradient(135deg, #bdceb3, #7d967d);
            color: var(--forest);
        }

        .gallery-placeholder strong {
            font: italic 28px Georgia, serif;
            font-weight: 400;
        }

        .gallery-placeholder span {
            font-size: 10px;
            letter-spacing: 1px;
        }

        .room-photo-label {
            position: absolute;
            top: 18px;
            left: 18px;
            z-index: 2;
            max-width: calc(100% - 36px);
            padding: 7px 12px;
            border: 1px solid #ffffff7a;
            border-radius: 5px;
            background: #f7f5eff2;
            color: var(--forest);
            font-size: 9px;
            letter-spacing: 1.1px;
            text-transform: uppercase;
            backdrop-filter: blur(8px);
        }

        .photo-count {
            position: absolute;
            right: 18px;
            bottom: 18px;
            z-index: 2;
            padding: 7px 11px;
            border: 1px solid #ffffff35;
            border-radius: 6px;
            background: #112d268c;
            color: #ffffff;
            font-size: 10px;
            backdrop-filter: blur(8px);
        }

        .gallery-control {
            position: absolute;
            top: 50%;
            z-index: 3;
            display: grid;
            place-items: center;
            width: 44px;
            height: 44px;
            padding: 0;
            border: 1px solid #ffffff90;
            border-radius: 50%;
            background: #f7f5efed;
            color: var(--forest);
            font-size: 19px;
            transform: translateY(-50%);
            box-shadow: 0 4px 15px #102b2412;
            backdrop-filter: blur(8px);
            transition: background .25s, color .25s;
        }

        .gallery-control:hover {
            background: var(--forest);
            color: #ffffff;
        }

        .gallery-control--previous {
            left: 16px;
        }

        .gallery-control--next {
            right: 16px;
        }

        .room-body {
            padding: 26px;
        }

        .thumbnails {
            display: flex;
            gap: 9px;
            overflow-x: auto;
            padding: 3px 3px 12px;
            margin: -3px -3px 17px;
            scrollbar-width: thin;
            scrollbar-color: #a6b497 transparent;
            overscroll-behavior-x: contain;
        }

        .thumbnail {
            position: relative;
            display: block;
            width: 72px;
            height: 54px;
            flex: 0 0 72px;
            padding: 3px;
            overflow: hidden;
            border: 1px solid transparent;
            border-radius: 8px;
            opacity: .65;
            transition:
                opacity .25s,
                border-color .25s,
                transform .25s;
        }

        .thumbnail:hover {
            opacity: 1;
            transform: translateY(-2px);
        }

        .thumbnail.is-active {
            border-color: var(--forest);
            opacity: 1;
        }

        .thumbnail img {
            width: 100%;
            height: 100%;
            border-radius: 5px;
            object-fit: cover;
        }

        .room-type {
            color: #74835e;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1.7px;
            text-transform: uppercase;
        }

        .room-title-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 22px;
            margin-top: 10px;
        }

        .room-name {
            min-width: 0;
        }

        .room-name h2 {
            margin: 0 0 9px;
            font: 400 32px/1.15 Georgia, serif;
            letter-spacing: -.9px;
            overflow-wrap: anywhere;
        }

        .room-number {
            color: var(--muted);
            font-size: 10px;
            letter-spacing: .5px;
        }

        .price {
            max-width: 50%;
            flex-shrink: 0;
            text-align: right;
        }

        .price strong {
            display: block;
            color: var(--forest);
            font-size: 25px;
            font-weight: 500;
            letter-spacing: -.8px;
            overflow-wrap: anywhere;
        }

        .price .currency {
            margin-right: 3px;
            color: var(--muted);
            font-size: 9px;
            font-weight: 400;
            letter-spacing: .7px;
        }

        .price small {
            display: block;
            margin-top: 3px;
            color: var(--muted);
            font-size: 10px;
        }

        .room-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-top: 25px;
            padding-top: 21px;
            border-top: 1px solid var(--line);
        }

        .room-footer p {
            max-width: 230px;
            margin: 0;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.8;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 22px;
            min-height: 48px;
            padding: 12px 20px;
            border: 1px solid transparent;
            border-radius: 7px;
            background: var(--forest);
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            text-align: center;
            transition:
                transform .3s var(--ease),
                background .25s,
                box-shadow .3s;
        }

        .btn span {
            display: inline-block;
            transition: transform .3s var(--ease);
        }

        .btn:hover {
            transform: translateY(-2px);
            background: #295744;
            box-shadow: 0 8px 18px #193d3217;
        }

        .btn:hover span {
            transform: translateX(4px);
        }

        .room-footer .btn {
            flex-shrink: 0;
        }

        .btn-gold {
            background: var(--gold);
            color: var(--dark);
        }

        .btn-gold:hover {
            background: #f1cd96;
        }

        /* Supporting information */

        .booking-note {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 30px;
            margin-top: 40px;
            padding: 28px 32px;
            border: 1px solid #d1dac5;
            border-radius: 12px;
            background: var(--sage);
        }

        .booking-note h3 {
            margin: 0 0 7px;
            font: 400 24px/1.2 Georgia, serif;
            letter-spacing: -.5px;
        }

        .booking-note p {
            max-width: 680px;
            margin: 0;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.9;
        }

        .text-link {
            display: inline-flex;
            align-items: center;
            gap: 18px;
            min-height: 44px;
            flex-shrink: 0;
            border-bottom: 1px solid #a5b198;
            font-size: 11px;
            font-weight: 700;
        }

        .text-link span {
            transition: transform .3s var(--ease);
        }

        .text-link:hover span {
            transform: translate(3px, -2px);
        }

        .empty {
            padding: 60px 35px;
            border: 1px solid var(--line);
            border-radius: 18px;
            background: var(--paper);
            text-align: center;
        }

        .empty-mark {
            display: grid;
            place-items: center;
            width: 60px;
            height: 60px;
            margin: 0 auto 24px;
            border: 1px solid #bdc9b0;
            border-radius: 50%;
            color: #7c8b64;
            font-size: 27px;
        }

        .empty h2 {
            margin-bottom: 16px;
            font: 400 34px/1.15 Georgia, serif;
            letter-spacing: -.8px;
        }

        .empty p {
            margin-bottom: 25px;
            color: var(--muted);
            font-size: 13px;
        }

        .help-section {
            position: relative;
            overflow: hidden;
            padding: 65px 0;
            background: var(--forest);
            color: #ffffff;
        }

        .help-section::after {
            content: '';
            position: absolute;
            top: -120px;
            right: -60px;
            width: 350px;
            height: 350px;
            border: 1px solid #ffffff14;
            border-radius: 50%;
            pointer-events: none;
        }

        .help-inner {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 35px;
        }

        .help-section .eyebrow {
            margin-bottom: 17px;
            color: #d1bc8a;
        }

        .help-section h2 {
            margin: 0 0 14px;
            font: 400 clamp(32px, 3.4vw, 47px)/1.1 Georgia, serif;
            letter-spacing: -1.3px;
        }

        .help-section p {
            max-width: 550px;
            margin: 0;
            color: #c4d0bc;
            font-size: 12px;
            line-height: 1.85;
        }

        .help-inner > .btn {
            flex-shrink: 0;
        }

        .site-footer {
            padding: 27px 0;
            background: var(--dark);
            color: #b9c6b0;
        }

        .footer-inner,
        .footer-links {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

        .footer-inner {
            justify-content: space-between;
            gap: 20px;
            font-size: 10px;
        }

        .footer-links {
            gap: 23px;
        }

        .footer-links a:hover {
            color: var(--gold);
        }

        @keyframes rise-in {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 1100px) {
            .container {
                width: calc(100% - 64px);
            }

            .room-title-row {
                flex-direction: column;
                gap: 16px;
            }

            .price {
                max-width: 100%;
                text-align: left;
            }

            .price strong,
            .price small {
                display: inline;
            }

            .price small {
                margin-left: 7px;
            }

            .room-footer {
                align-items: stretch;
                flex-direction: column;
                gap: 17px;
            }

            .room-footer p {
                max-width: none;
            }

            .room-footer .btn {
                justify-content: space-between;
            }

            .access-caption {
                display: none;
            }
        }

        @media (max-width: 800px) {
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

            .mobile-menu {
                display: block;
            }

            .intro {
                gap: 25px;
                padding: 40px 0 48px;
            }

            h1 {
                font-size: 61px;
                letter-spacing: -2.4px;
            }

            .intro-note {
                width: 180px;
            }

            .journey-step {
                padding: 20px 16px;
                gap: 10px;
            }

            .journey-step small {
                font-size: 9px;
            }

            .rooms {
                gap: 22px;
            }

            .room-body {
                padding: 22px;
            }

            .room-name h2 {
                font-size: 29px;
            }

            .booking-note {
                align-items: flex-start;
                flex-direction: column;
                gap: 17px;
            }
        }

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
                margin-top: 4px;
                font-size: 6px;
                letter-spacing: 1.15px;
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
                padding: 0 1px;
                font-size: 10px;
            }

            .account-link {
                max-width: 86px;
                padding: 7px 10px;
                font-size: 10px;
                text-align: center;
            }

            .breadcrumbs {
                gap: 9px;
                padding-top: 24px;
                font-size: 9px;
            }

            .intro {
                flex-direction: column;
                align-items: flex-start;
                padding: 32px 0 37px;
                gap: 25px;
            }

            .intro .eyebrow {
                margin-bottom: 19px;
                font-size: 8px;
                letter-spacing: 2px;
            }

            h1 {
                font-size: clamp(46px, 12vw, 65px);
                letter-spacing: -2px;
                margin-bottom: 20px;
            }

            .intro-description {
                max-width: 360px;
                font-size: 12px;
            }

            .intro-note {
                display: flex;
                align-items: center;
                gap: 16px;
                width: 100%;
                padding: 20px 0 0;
                border-top: 1px solid #cdd6c3;
            }

            .intro-note-number {
                margin: 0;
                font-size: 35px;
            }

            .intro-note strong {
                margin-bottom: 3px;
                font-size: 11px;
            }

            .intro-note p {
                font-size: 10px;
            }

            .journey-step {
                padding: 18px 8px;
                align-items: flex-start;
                flex-direction: column;
                gap: 10px;
            }

            .journey-step:first-child {
                padding-left: 0;
            }

            .journey-number {
                width: 26px;
                height: 26px;
                font-size: 9px;
            }

            .journey-step strong {
                font-size: 9px;
            }

            .journey-step small {
                display: none;
            }

            .rooms-section {
                padding: 35px 0 45px;
            }

            .collection-heading {
                gap: 12px;
                margin-bottom: 23px;
            }

            .collection-heading h2 {
                font-size: 25px;
            }

            .collection-meta {
                font-size: 9px;
            }

            .rooms {
                grid-template-columns: 1fr;
                gap: 26px;
            }

            .room-card {
                border-radius: 15px;
            }

            .gallery-main {
                aspect-ratio: 16 / 11;
            }

            .room-photo-label {
                top: 14px;
                left: 14px;
                font-size: 8px;
            }

            .photo-count {
                right: 14px;
                bottom: 14px;
                font-size: 9px;
            }

            .gallery-control--previous {
                left: 12px;
            }

            .gallery-control--next {
                right: 12px;
            }

            .room-body {
                padding: 22px;
            }

            .room-title-row {
                gap: 15px;
            }

            .room-name h2 {
                font-size: 31px;
            }

            .price strong {
                font-size: 24px;
            }

            .thumbnail {
                width: 65px;
                height: 50px;
                flex-basis: 65px;
            }

            .booking-note {
                margin-top: 28px;
                padding: 25px 22px;
            }

            .booking-note h3 {
                font-size: 25px;
            }

            .booking-note p {
                font-size: 10px;
            }

            .help-section {
                padding: 45px 0;
            }

            .help-inner {
                align-items: flex-start;
                flex-direction: column;
                gap: 25px;
            }

            .help-section h2 {
                font-size: 37px;
            }

            .help-section p {
                font-size: 11px;
            }

            .help-inner > .btn {
                width: 100%;
                justify-content: space-between;
            }

            .empty {
                padding: 40px 23px;
            }

            .empty h2 {
                font-size: 30px;
            }

            .footer-inner {
                gap: 18px;
                font-size: 9px;
            }

            .footer-links {
                gap: 19px;
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

            h1 {
                font-size: 45px;
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

            .room-card:hover,
            .room-card:hover .gallery-main > img,
            .btn:hover,
            .thumbnail:hover {
                transform: none;
            }
        }
    </style>
</head>

<body>
    <a href="#main-content" class="skip-link">
        Skip to rooms
    </a>

    <header class="site-header">
        <div class="container nav">
            <a
                href="{{ route('welcome') }}"
                class="brand"
                aria-label="McLovin Resort home"
            >
                @if($logoExists)
                    <img
                        src="{{ asset('images/logo.jpg') }}"
                        alt=""
                        width="44"
                        height="44"
                        class="brand-logo"
                    >
                @else
                    <span class="brand-mark" aria-hidden="true">
                        M
                    </span>
                @endif

                <span>
                    <span class="brand-name">McLovin Resort</span>

                    <span class="brand-subtitle">
                        A little closer to nature
                    </span>
                </span>
            </a>

            <nav class="desktop-nav" aria-label="Main navigation">
                <a href="{{ route('welcome') }}" class="nav-link">
                    Home
                </a>

                <a href="#rooms" class="nav-link">
                    Our rooms
                </a>

                <a
                    href="{{ route('welcome') }}#contact"
                    class="nav-link"
                >
                    Contact
                </a>
            </nav>

            <div class="nav-tools">
                @if($isGuest)
                    <a
                        href="{{ route('guest.dashboard') }}"
                        class="account-link"
                    >
                        My Bookings ↗
                    </a>
                @elseif($isStaff)
                    <a
                        href="{{ route('dashboard') }}"
                        class="account-link"
                    >
                        Staff Dashboard ↗
                    </a>
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

                <details class="mobile-menu" id="mobileMenu">
                    <summary aria-label="Navigation menu">
                        <span
                            class="menu-lines"
                            aria-hidden="true"
                        ></span>
                    </summary>

                    <nav
                        class="mobile-links"
                        aria-label="Mobile navigation"
                    >
                        <a href="{{ route('welcome') }}">
                            Home
                        </a>

                        <a href="#rooms">
                            Our rooms
                        </a>

                        <a href="{{ route('welcome') }}#contact">
                            Contact the resort
                        </a>

                        @if($isGuest)
                            <a href="{{ route('guest.dashboard') }}">
                                My Bookings ↗
                            </a>
                        @elseif($isStaff)
                            <a href="{{ route('dashboard') }}">
                                Staff Dashboard ↗
                            </a>
                        @elseif(!$currentUser)
                            <a href="{{ route('guest.register') }}">
                                Create guest account ↗
                            </a>
                        @endif
                    </nav>
                </details>
            </div>
        </div>

        <div class="scroll-progress" aria-hidden="true"></div>
    </header>

    <main id="main-content">
        <section
            class="intro-section"
            aria-labelledby="page-title"
        >
            <div class="container">
                <nav class="breadcrumbs" aria-label="Breadcrumb">
                    <a href="{{ route('welcome') }}">Home</a>

                    <span aria-hidden="true">/</span>

                    <span aria-current="page">Choose your room</span>
                </nav>

                <div class="intro">
                    <div>
                        <span class="eyebrow">
                            A little space. A different pace.
                        </span>

                        <h1 id="page-title">
                            Find your<br>
                            <em>kind of stay.</em>
                        </h1>

                        <p class="intro-description">
                            Take a look around. Explore the photos,
                            find a room that feels right, and make
                            a little time for yourself.
                        </p>
                    </div>

                    <div class="intro-note">
                        <span
                            class="intro-note-number"
                            aria-hidden="true"
                        >
                            01
                        </span>

                        <div>
                            <strong>Start with your favourite room.</strong>

                            <p>
                                You’ll choose your dates and add
                                your details on the next page.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <nav class="journey-strip" aria-label="Booking progress">
            <ol
                class="container journey"
                style="list-style: none; margin-block: 0; padding-left: 0;"
            >
                <li class="journey-step is-current" aria-current="step">
                    <span class="journey-number" aria-hidden="true">
                        01
                    </span>

                    <div>
                        <strong>Choose your room</strong>
                        <small>Find your favourite space</small>
                    </div>
                </li>

                <li class="journey-step">
                    <span class="journey-number" aria-hidden="true">
                        02
                    </span>

                    <div>
                        <strong>Dates &amp; details</strong>
                        <small>Plan the stay your way</small>
                    </div>
                </li>

                <li class="journey-step">
                    <span class="journey-number" aria-hidden="true">
                        03
                    </span>

                    <div>
                        <strong>Payment &amp; review</strong>
                        <small>Our team confirms your stay</small>
                    </div>
                </li>
            </ol>
        </nav>

        <section
            class="rooms-section"
            id="rooms"
            aria-labelledby="rooms-title"
        >
            <div class="container">
                <div class="collection-heading">
                    <h2 id="rooms-title">The room collection.</h2>

                    <span class="collection-meta">
                        {{ $rooms->count() }}
                        {{ $rooms->count() === 1 ? 'room listed' : 'rooms listed' }}
                    </span>
                </div>

                @if($rooms->isNotEmpty())
                    <div class="rooms">
                        @foreach($rooms as $room)
                            @php
                                $photos = [];

                                foreach ($room->images as $image) {
                                    if (!$image->image_path) {
                                        continue;
                                    }

                                    $path = ltrim(
                                        $image->image_path,
                                        '/'
                                    );

                                    $photos[] = str_starts_with(
                                        $path,
                                        'storage/'
                                    )
                                        ? asset($path)
                                        : asset('storage/' . $path);
                                }

                                $photoTotal = count($photos);

                                $galleryId = 'room-gallery-' . $room->id;
                            @endphp

                            <article
                                class="room-card"
                                data-gallery
                                aria-labelledby="room-title-{{ $room->id }}"
                            >
                                <div
                                    class="gallery-main"
                                    id="{{ $galleryId }}"
                                >
                                    @if($photoTotal > 0)
                                        <img
                                            src="{{ $photos[0] }}"
                                            alt="{{ $room->name }}"
                                            data-main-image
                                            width="800"
                                            height="500"
                                            loading="lazy"
                                            decoding="async"
                                        >

                                        <span
                                            class="photo-count"
                                            data-photo-count
                                            aria-live="polite"
                                            aria-atomic="true"
                                        >
                                            1 / {{ $photoTotal }} photos
                                        </span>

                                        @if($photoTotal > 1)
                                            <button
                                                type="button"
                                                class="gallery-control gallery-control--previous"
                                                data-gallery-previous
                                                aria-label="Previous photo of {{ $room->name }}"
                                                aria-controls="{{ $galleryId }}"
                                                hidden
                                            >
                                                <span aria-hidden="true">
                                                    ←
                                                </span>
                                            </button>

                                            <button
                                                type="button"
                                                class="gallery-control gallery-control--next"
                                                data-gallery-next
                                                aria-label="Next photo of {{ $room->name }}"
                                                aria-controls="{{ $galleryId }}"
                                                hidden
                                            >
                                                <span aria-hidden="true">
                                                    →
                                                </span>
                                            </button>
                                        @endif
                                    @else
                                        <div class="gallery-placeholder">
                                            <strong>McLovin Resort</strong>
                                            <span>
                                                Room photos coming soon
                                            </span>
                                        </div>
                                    @endif

                                    <span class="room-photo-label">
                                        {{ $room->type ?: 'Guest room' }}
                                    </span>
                                </div>

                                <div class="room-body">
                                    @if($photoTotal > 1)
                                        <div
                                            class="thumbnails"
                                            role="group"
                                            aria-label="Photos of {{ $room->name }}"
                                        >
                                            @foreach($photos as $photo)
                                                <a
                                                    href="{{ $photo }}"
                                                    class="thumbnail {{ $loop->first ? 'is-active' : '' }}"
                                                    data-thumbnail
                                                    aria-label="View photo {{ $loop->iteration }} of {{ $room->name }}"
                                                    @if($loop->first)
                                                        aria-current="true"
                                                    @endif
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                >
                                                    <img
                                                        src="{{ $photo }}"
                                                        alt=""
                                                        width="72"
                                                        height="54"
                                                        loading="lazy"
                                                        decoding="async"
                                                    >
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif

                                    <span class="room-type">
                                        Your own space to unwind
                                    </span>

                                    <div class="room-title-row">
                                        <div class="room-name">
                                            <h2 id="room-title-{{ $room->id }}">
                                                {{ $room->name }}
                                            </h2>

                                            <span class="room-number">
                                                Room {{ $room->number }}
                                            </span>
                                        </div>

                                        <div class="price">
                                            <strong>
                                                <span class="currency">
                                                    BDT
                                                </span>

                                                {{ number_format((float) $room->price, 2) }}
                                            </strong>

                                            <small>per night</small>
                                        </div>
                                    </div>

                                    <div class="room-footer">
                                        <p>
                                            Choose your dates next.
                                            Availability is checked
                                            for your selected stay.
                                        </p>

                                        <a
                                            href="{{ route('public.book', ['room' => $room->id]) }}"
                                            class="btn"
                                            aria-label="Select {{ $room->name }} and continue"
                                        >
                                            Select room

                                            <span aria-hidden="true">
                                                →
                                            </span>
                                        </a>
                                    </div>

                                    <p
                                        class="visually-hidden"
                                        data-gallery-message
                                        role="status"
                                    ></p>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <aside class="booking-note">
                        <div>
                            <h3>A little detail before you book.</h3>

                            <p>
                                Prices shown are current nightly room
                                prices. Selecting a room takes you
                                to the booking form, where you can
                                choose your dates and check availability.
                            </p>
                        </div>

                        <a
                            href="{{ route('welcome') }}#contact"
                            class="text-link"
                        >
                            Ask our team
                            <span aria-hidden="true">↗</span>
                        </a>
                    </aside>
                @else
                    <div class="empty">
                        <span class="empty-mark" aria-hidden="true">
                            ✳
                        </span>

                        <h2>
                            A little refresh<br>
                            is on the way.
                        </h2>

                        <p>
                            Our room listings are being updated.
                            Contact the resort team for help
                            planning your stay.
                        </p>

                        <a
                            href="{{ route('welcome') }}#contact"
                            class="btn"
                        >
                            Contact the resort
                            <span aria-hidden="true">↗</span>
                        </a>
                    </div>
                @endif
            </div>
        </section>

        <section
            class="help-section"
            aria-labelledby="help-title"
        >
            <div class="container help-inner">
                <div>
                    <span class="eyebrow">
                        We’re here to help
                    </span>

                    <h2 id="help-title">
                        Your getaway.<br>
                        A little guidance.
                    </h2>

                    <p>
                        Unsure which room to choose? Talk to our
                        team about your visit, room options or
                        booking questions.
                    </p>
                </div>

                <a
                    href="{{ route('welcome') }}#contact"
                    class="btn btn-gold"
                >
                    Talk to our team
                    <span aria-hidden="true">↗</span>
                </a>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
            <span>
                © {{ date('Y') }} McLovin Resort.
                All rights reserved.
            </span>

            <nav class="footer-links" aria-label="Footer navigation">
                <a href="{{ route('welcome') }}">
                    Home
                </a>

                <a href="{{ route('welcome') }}#contact">
                    Contact
                </a>

                @if(!$currentUser)
                    <a href="{{ route('guest.login') }}">
                        Guest Login
                    </a>

                    <a href="{{ route('login') }}">
                        Staff Login
                    </a>
                @elseif($isGuest)
                    <a href="{{ route('guest.dashboard') }}">
                        My Bookings
                    </a>
                @elseif($isStaff)
                    <a href="{{ route('dashboard') }}">
                        Staff Dashboard
                    </a>
                @endif
            </nav>
        </div>
    </footer>

    <script>
        (() => {
            const reduceMotion = window.matchMedia(
                '(prefers-reduced-motion: reduce)'
            );

            const activeAnimations = new Set();

            function trackAnimation(animation) {
                activeAnimations.add(animation);

                animation.finished
                    .then(() => activeAnimations.delete(animation))
                    .catch(() => activeAnimations.delete(animation));

                return animation;
            }

            /* Header and scroll progress */

            const header = document.querySelector('.site-header');
            const progress = document.querySelector('.scroll-progress');

            let scrollScheduled = false;

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

                scrollScheduled = false;
            }

            window.addEventListener('scroll', () => {
                if (!scrollScheduled) {
                    scrollScheduled = true;
                    requestAnimationFrame(updateHeader);
                }
            }, { passive: true });

            window.addEventListener('resize', updateHeader);
            window.addEventListener('load', updateHeader);

            updateHeader();

            /* Mobile navigation */

            const menu = document.getElementById('mobileMenu');
            const desktop = window.matchMedia('(min-width: 801px)');

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

            function handleViewportChange() {
                if (desktop.matches) {
                    closeMenu();
                }
            }

            if (desktop.addEventListener) {
                desktop.addEventListener('change', handleViewportChange);
            } else {
                desktop.addListener(handleViewportChange);
            }

            window.addEventListener('pageshow', () => closeMenu());

            /* Room photo galleries */

            document.querySelectorAll('[data-gallery]').forEach((gallery) => {
                const mainImage = gallery.querySelector('[data-main-image]');
                const counter = gallery.querySelector('[data-photo-count]');
                const message = gallery.querySelector('[data-gallery-message]');

                const thumbnails = Array.from(
                    gallery.querySelectorAll('[data-thumbnail]')
                );

                const previousButton = gallery.querySelector(
                    '[data-gallery-previous]'
                );

                const nextButton = gallery.querySelector(
                    '[data-gallery-next]'
                );

                if (!mainImage || thumbnails.length === 0) {
                    return;
                }

                const baseAlt = mainImage.alt;
                let activeIndex = 0;
                let requestNumber = 0;
                let imageAnimation = null;

                function showPhoto(index) {
                    const nextIndex =
                        (index + thumbnails.length) % thumbnails.length;

                    const thumbnail = thumbnails[nextIndex];

                    if (!thumbnail) {
                        return;
                    }

                    const currentRequest = ++requestNumber;

                    if (message) {
                        message.textContent = '';
                    }

                    const pendingImage = new Image();

                    pendingImage.onload = () => {
                        if (currentRequest !== requestNumber) {
                            return;
                        }

                        mainImage.src = thumbnail.href;
                        mainImage.alt =
                            `${baseAlt} — photo ${nextIndex + 1} ` +
                            `of ${thumbnails.length}`;

                        activeIndex = nextIndex;

                        thumbnails.forEach((item, itemIndex) => {
                            const isActive = itemIndex === activeIndex;

                            item.classList.toggle('is-active', isActive);

                            if (isActive) {
                                item.setAttribute('aria-current', 'true');
                            } else {
                                item.removeAttribute('aria-current');
                            }
                        });

                        if (counter) {
                            counter.textContent =
                                `${activeIndex + 1} / ` +
                                `${thumbnails.length} photos`;
                        }

                        if (
                            !reduceMotion.matches &&
                            'animate' in Element.prototype
                        ) {
                            imageAnimation?.cancel();

                            imageAnimation = trackAnimation(
                                mainImage.animate([
                                    { opacity: .35 },
                                    { opacity: 1 }
                                ], {
                                    duration: 320,
                                    easing: 'ease-out'
                                })
                            );
                        }
                    };

                    pendingImage.onerror = () => {
                        if (currentRequest !== requestNumber) {
                            return;
                        }

                        if (message) {
                            message.textContent =
                                'This photo could not be loaded. ' +
                                'Please try another photo.';
                        }
                    };

                    pendingImage.src = thumbnail.href;
                }

                thumbnails.forEach((thumbnail, index) => {
                    thumbnail.addEventListener('click', (event) => {
                        if (
                            event.ctrlKey ||
                            event.metaKey ||
                            event.shiftKey ||
                            event.altKey ||
                            event.button !== 0
                        ) {
                            return;
                        }

                        event.preventDefault();
                        showPhoto(index);
                    });

                    thumbnail.addEventListener('keydown', (event) => {
                        let nextIndex;

                        if (event.key === 'ArrowRight') {
                            nextIndex = (index + 1) % thumbnails.length;
                        } else if (event.key === 'ArrowLeft') {
                            nextIndex =
                                (index - 1 + thumbnails.length) %
                                thumbnails.length;
                        } else if (event.key === 'Home') {
                            nextIndex = 0;
                        } else if (event.key === 'End') {
                            nextIndex = thumbnails.length - 1;
                        } else {
                            return;
                        }

                        event.preventDefault();
                        thumbnails[nextIndex].focus();
                        showPhoto(nextIndex);
                    });
                });

                if (previousButton) {
                    previousButton.hidden = false;

                    previousButton.addEventListener('click', () => {
                        showPhoto(activeIndex - 1);
                    });
                }

                if (nextButton) {
                    nextButton.hidden = false;

                    nextButton.addEventListener('click', () => {
                        showPhoto(activeIndex + 1);
                    });
                }
            });

            /* Gentle scroll reveals */

            let observer;

            if (
                !reduceMotion.matches &&
                'IntersectionObserver' in window &&
                'animate' in Element.prototype
            ) {
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
                            130
                        );

                        trackAnimation(
                            entry.target.animate([
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
                            })
                        );
                    });
                }, {
                    threshold: .08
                });

                document.querySelectorAll(
                    '.journey-step, .collection-heading, ' +
                    '.room-card, .booking-note, .empty, .help-inner'
                ).forEach((element) => {
                    observer.observe(element);
                });
            }

            function handleMotionPreference() {
                if (reduceMotion.matches) {
                    observer?.disconnect();

                    activeAnimations.forEach((animation) => {
                        animation.cancel();
                    });

                    activeAnimations.clear();
                }
            }

            if (reduceMotion.addEventListener) {
                reduceMotion.addEventListener(
                    'change',
                    handleMotionPreference
                );
            } else {
                reduceMotion.addListener(handleMotionPreference);
            }
        })();
    </script>
</body>
</html>