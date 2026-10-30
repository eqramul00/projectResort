@php
    $authMode = $authMode ?? 'guest';

    $isStaff = $authMode === 'staff';
    $isRegister = $authMode === 'register';

    $formAction = $isStaff
        ? route('login.attempt')
        : ($isRegister
            ? route('guest.register.store')
            : route('guest.login.attempt'));

    $heading = $isRegister
        ? 'Make yourself at home.'
        : 'Welcome back.';

    $description = $isRegister
        ? 'Create your McLovin Hotel guest account.'
        : ($isStaff
            ? 'Sign in to your hotel management workspace.'
            : 'Sign in to view your bookings and payment status.');
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta name="color-scheme" content="dark">

    <title>
        {{ $isRegister
            ? 'Guest Registration'
            : ($isStaff ? 'Staff Login' : 'Guest Login') }}
        | McLovin Hotel
    </title>

    <style>
        :root {
            color-scheme: dark;
            --cream: #f6ead4;
            --gold: #dbb674;
            --text: #f7f1e7;
            --muted: #bcb6ad;
        }

        * {
            box-sizing: border-box;
        }

        html {
            height: 100%;
        }

        body {
            margin: 0;
            height: 100vh;
            height: 100dvh;
            display: grid;
            grid-template-rows: auto minmax(0, 1fr);
            overflow: hidden;
            background: #090e14;
            color: var(--text);
            font: 16px/1.5 Arial, Helvetica, sans-serif;
        }

        button,
        input {
            font: inherit;
        }

        button,
        a,
        input {
            -webkit-tap-highlight-color: transparent;
        }

        button {
            cursor: pointer;
        }

        a {
            color: inherit;
        }

        :focus-visible {
            outline: 2px solid var(--gold);
            outline-offset: 4px;
        }

        .background {
            position: fixed;
            inset: 0;
            z-index: -2;
            overflow: hidden;
            pointer-events: none;
            background:
                radial-gradient(
                    ellipse at 30% 75%,
                    rgba(171, 112, 43, .12),
                    transparent 55%
                ),
                linear-gradient(
                    120deg,
                    #080d13,
                    #14202b 55%,
                    #080c11
                );
        }

        .city {
            position: absolute;
            inset: 20% -5% 0;
            display: flex;
            align-items: flex-end;
            gap: 5%;
            opacity: .35;
            filter: blur(6px);
        }

        .city span {
            flex: 0 0 11%;
            height: 72%;
            border: 9px solid #101922;
            background:
                repeating-linear-gradient(
                    0deg,
                    transparent 0 23px,
                    rgba(236, 184, 103, .6) 24px 29px,
                    transparent 30px 47px
                ),
                repeating-linear-gradient(
                    90deg,
                    #111c27 0 15px,
                    rgba(232, 180, 102, .3) 16px 20px,
                    #101a24 21px 36px
                );
        }

        .city span:nth-child(even) {
            height: 95%;
        }

        .city span:nth-child(3n) {
            height: 50%;
        }

        .window-frame {
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(
                90deg,
                transparent 0 24%,
                rgba(2, 5, 8, .85) 24% 25%
            );
        }

        .desk {
            position: fixed;
            inset: auto 0 0;
            z-index: -1;
            height: 18vh;
            border-top: 1px solid rgba(218, 176, 106, .12);
            background: linear-gradient(
                170deg,
                rgba(117, 79, 37, .12),
                #070a0e 75%
            );
            pointer-events: none;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            min-width: 0;
            padding: 18px clamp(20px, 4vw, 64px);
            border-bottom: 1px solid rgba(255, 255, 255, .09);
        }

        .brand {
            font-size: clamp(12px, 1.4vw, 18px);
            letter-spacing: .25em;
            text-decoration: none;
        }

        .home-link {
            flex-shrink: 0;
            padding: 7px 0;
            font-size: 13px;
            text-decoration: none;
        }

        .home-link:hover {
            color: var(--gold);
        }

        /*
         * The remaining browser height belongs to this grid row.
         * min-height: 0 allows its children to shrink correctly.
         */
        .auth-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            align-items: center;
            gap: clamp(24px, 5vw, 80px);
            width: 100%;
            max-width: 1180px;
            height: 100%;
            min-height: 0;
            margin: 0 auto;
            padding: clamp(16px, 3vh, 32px) 40px;
        }

        .lamp-area {
            position: relative;
            justify-self: center;
            width: min(100%, 54vh);
            width: min(100%, 54dvh);
            min-width: 0;
            aspect-ratio: 500 / 550;
        }

        .lamp-art {
            display: block;
            width: 100%;
            height: 100%;
        }

        .lamp-light {
            opacity: 1;
            transition: opacity .6s ease;
        }

        .lamp-off .lamp-light {
            opacity: 0;
        }

        .welcome-note {
            position: absolute;
            top: 48%;
            left: 0;
            max-width: 150px;
            margin: 0;
            color: #e9ddca;
            font-size: clamp(12px, 1.2vw, 15px);
        }

        .lamp-button {
            position: absolute;
            top: 59%;
            left: 53.6%;
            min-width: 110px;
            min-height: 48px;
            padding: 10px;
            border: 0;
            border-radius: 8px;
            background: transparent;
            color: var(--cream);
            font-size: 12px;
            transform: translateX(-50%);
        }

        .lamp-button::before {
            content: "";
            display: block;
            width: 1px;
            height: 18px;
            margin: 0 auto 10px;
            background: #cdb58e;
        }

        .lamp-button:active {
            transform: translate(-50%, 4px);
        }

        .auth-card {
            width: 100%;
            max-width: 480px;
            max-height: 100%;
            min-height: 0;
            justify-self: center;
            overflow-y: auto;
            overscroll-behavior: contain;
            scrollbar-width: thin;
            scrollbar-color: #776143 transparent;
            padding: clamp(22px, 3vh, 36px);
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 18px;
            background: rgba(17, 19, 23, .9);
            backdrop-filter: blur(18px);
            box-shadow: 0 25px 80px rgba(0, 0, 0, .35);
            opacity: 1;
            visibility: visible;
            transition:
                opacity .45s ease,
                transform .45s ease,
                visibility .45s;
        }

        .lamp-off .auth-card {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transform: translateY(8px);
        }

        h1 {
            margin: 0 0 9px;
            font-size: clamp(28px, 3vw, 38px);
            font-weight: 400;
            line-height: 1.15;
            letter-spacing: -.04em;
        }

        .description {
            margin: 0 0 22px;
            color: var(--muted);
            font-size: 14px;
        }

        .account-tabs {
            display: flex;
            gap: 4px;
            margin-bottom: 22px;
            padding: 4px;
            border: 1px solid rgba(255, 255, 255, .19);
            border-radius: 12px;
        }

        .account-tabs a {
            flex: 1;
            padding: 9px;
            border-radius: 9px;
            font-size: 14px;
            text-align: center;
            text-decoration: none;
        }

        .account-tabs a[aria-current="page"] {
            background: var(--cream);
            color: #181612;
            font-weight: 700;
        }

        .field {
            margin-bottom: 16px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-size: 13px;
        }

        input {
            display: block;
            width: 100%;
            min-width: 0;
            height: 48px;
            padding: 11px 14px;
            border: 1px solid rgba(255, 255, 255, .25);
            border-radius: 9px;
            background: rgba(7, 10, 15, .65);
            color: #fff;
        }

        input:focus {
            outline: 2px solid var(--gold);
            outline-offset: 2px;
        }

        input[aria-invalid="true"] {
            border-color: #efa5a5;
        }

        .password-wrap {
            position: relative;
        }

        .password-wrap input {
            padding-right: 75px;
        }

        .password-toggle {
            position: absolute;
            top: 2px;
            right: 5px;
            min-width: 62px;
            height: 44px;
            border: 0;
            border-radius: 7px;
            background: transparent;
            color: var(--cream);
            font-size: 12px;
        }

        .submit-button {
            width: 100%;
            min-height: 48px;
            margin-top: 3px;
            padding: 12px 16px;
            border: 0;
            border-radius: 9px;
            background: var(--cream);
            color: #191611;
            font-weight: 700;
            transition: background .2s;
        }

        .submit-button:hover {
            background: #fff4de;
        }

        .submit-button:disabled {
            cursor: wait;
            opacity: .7;
        }

        .account-link {
            margin: 19px 0 0;
            color: var(--muted);
            font-size: 13px;
            text-align: center;
        }

        .account-link a {
            color: var(--cream);
            text-underline-offset: 4px;
        }

        .help-text {
            margin: 9px 0 0;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.6;
        }

        .registration-note {
            margin-top: 18px;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, .1);
        }

        .alert {
            margin-bottom: 17px;
            padding: 12px 14px;
            border: 1px solid rgba(212, 173, 104, .3);
            border-radius: 10px;
            background: rgba(212, 173, 104, .08);
            font-size: 13px;
        }

        .alert ul {
            margin: 0;
            padding-left: 18px;
        }

        .alert-error {
            border-color: rgba(239, 130, 130, .35);
            background: rgba(128, 38, 38, .2);
            color: #ffd0d0;
        }

        .alert-success {
            border-color: rgba(114, 190, 146, .3);
            background: rgba(30, 91, 54, .2);
            color: #c2f0d0;
        }

        /* Compact layout for shorter laptop screens. */
        @media (min-width: 761px) and (max-height: 720px) {
            .topbar {
                padding-top: 12px;
                padding-bottom: 12px;
            }

            .auth-layout {
                padding-top: 14px;
                padding-bottom: 14px;
            }

            .auth-card {
                padding: 22px 26px;
            }

            h1 {
                font-size: 30px;
            }

            .description,
            .account-tabs {
                margin-bottom: 16px;
            }

            .field {
                margin-bottom: 13px;
            }

            .account-link {
                margin-top: 15px;
            }
        }

        /* Mobile uses normal document scrolling. */
        @media (max-width: 760px) {
            html {
                height: auto;
            }

            body {
                display: block;
                height: auto;
                min-height: 100svh;
                overflow: auto;
            }

            .topbar {
                gap: 12px;
                padding: 16px 20px;
            }

            .brand {
                letter-spacing: .14em;
            }

            .home-link {
                font-size: 12px;
            }

            .auth-layout {
                grid-template-columns: 1fr;
                gap: 0;
                max-width: 520px;
                height: auto;
                padding: 8px 20px 35px;
            }

            .lamp-area {
                width: min(100%, 300px);
                margin: 0 auto -10px;
            }

            .welcome-note {
                top: 43%;
                max-width: 105px;
                font-size: 12px;
            }

            .lamp-button {
                font-size: 11px;
            }

            .auth-card {
                max-height: none;
                overflow: visible;
                padding: 26px 22px;
            }

            .desk {
                height: 35vh;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>
</head>

<body>
    <div class="background" aria-hidden="true">
        <div class="city">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>

        <div class="window-frame"></div>
    </div>

    <div class="desk" aria-hidden="true"></div>

    <header class="topbar">
        <a class="brand" href="{{ route('welcome') }}">
            MCLOVIN HOTEL
        </a>

        <a class="home-link" href="{{ route('welcome') }}">
            Back to hotel &nbsp; →
        </a>
    </header>

    <main class="auth-layout">
        <section class="lamp-area" aria-label="Interactive desk lamp">
            <svg
                class="lamp-art"
                viewBox="0 0 500 550"
                aria-hidden="true"
                focusable="false"
            >
                <defs>
                    <linearGradient id="lampBrass">
                        <stop stop-color="#614219"/>
                        <stop offset=".3" stop-color="#ecc77a"/>
                        <stop offset=".48" stop-color="#9a6724"/>
                        <stop offset=".68" stop-color="#f5d48b"/>
                        <stop offset="1" stop-color="#503315"/>
                    </linearGradient>

                    <linearGradient
                        id="lampShade"
                        x1="0"
                        y1="0"
                        x2="1"
                        y2=".3"
                    >
                        <stop stop-color="#302416"/>
                        <stop offset=".42" stop-color="#ae7e38"/>
                        <stop offset=".7" stop-color="#63441f"/>
                        <stop offset="1" stop-color="#1b1917"/>
                    </linearGradient>

                    <linearGradient
                        id="lampBeam"
                        x1="0"
                        y1="0"
                        x2="0"
                        y2="1"
                    >
                        <stop
                            stop-color="#ffc76c"
                            stop-opacity=".32"
                        />

                        <stop
                            offset="1"
                            stop-color="#ffc76c"
                            stop-opacity="0"
                        />
                    </linearGradient>

                    <radialGradient id="lampPool">
                        <stop
                            stop-color="#eabd70"
                            stop-opacity=".3"
                        />

                        <stop
                            offset="1"
                            stop-color="#eabd70"
                            stop-opacity="0"
                        />
                    </radialGradient>
                </defs>

                <path
                    class="lamp-light"
                    d="M238 207 L397 207 L493 505 L115 505 Z"
                    fill="url(#lampBeam)"
                />

                <ellipse
                    class="lamp-light"
                    cx="290"
                    cy="505"
                    rx="210"
                    ry="35"
                    fill="url(#lampPool)"
                />

                <ellipse
                    cx="199"
                    cy="510"
                    rx="85"
                    ry="17"
                    fill="#050709"
                />

                <path
                    d="M124 492 Q198 473 273 492 L273 507 Q199 530 124 507Z"
                    fill="url(#lampShade)"
                    stroke="#b9904e"
                />

                <ellipse
                    cx="199"
                    cy="491"
                    rx="75"
                    ry="14"
                    fill="url(#lampBrass)"
                />

                <path
                    d="M198 487 L198 137 C198 28 300 22 308 101"
                    fill="none"
                    stroke="#291e12"
                    stroke-width="13"
                />

                <path
                    d="M198 487 L198 137 C198 28 300 22 308 101"
                    fill="none"
                    stroke="url(#lampBrass)"
                    stroke-width="8"
                />

                <path
                    d="M301 100 L319 98 L324 122 L303 124Z"
                    fill="url(#lampBrass)"
                />

                <path
                    d="M238 207 Q240 125 308 119 Q375 116 397 199 Q322 224 238 207Z"
                    fill="url(#lampShade)"
                    stroke="#c09650"
                    stroke-width="2"
                />

                <ellipse
                    cx="317"
                    cy="204"
                    rx="79"
                    ry="11"
                    fill="#392a18"
                />

                <ellipse
                    class="lamp-light"
                    cx="317"
                    cy="204"
                    rx="77"
                    ry="10"
                    fill="#ffedbf"
                />

                <path
                    d="M268 213 L268 316"
                    stroke="url(#lampBrass)"
                    stroke-width="3"
                    stroke-dasharray="2 2"
                />

                <circle
                    cx="268"
                    cy="323"
                    r="10"
                    fill="url(#lampBrass)"
                    stroke="#d8ad60"
                />
            </svg>

            <p class="welcome-note">
                A little light.<br>
                A warm welcome.
            </p>

            <button
                type="button"
                class="lamp-button"
                id="lampToggle"
                aria-controls="authCard"
                aria-pressed="true"
                aria-label="Turn lamp off and hide login form"
            >
                <span id="lampText">Tap to turn off</span>
            </button>
        </section>

        <section
            class="auth-card"
            id="authCard"
            aria-labelledby="authHeading"
        >
            <h1 id="authHeading">{{ $heading }}</h1>

            <p class="description">{{ $description }}</p>

            <nav class="account-tabs" aria-label="Choose account type">
                <a
                    href="{{ route('guest.login') }}"
                    @if(!$isStaff) aria-current="page" @endif
                >
                    Guest
                </a>

                <a
                    href="{{ route('login') }}"
                    @if($isStaff) aria-current="page" @endif
                >
                    Staff
                </a>
            </nav>

            @if(session('success'))
                <div class="alert alert-success" role="status">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('status'))
                <div class="alert" role="status">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error" role="alert">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                method="POST"
                action="{{ $formAction }}"
                id="authForm"
            >
                @csrf

                @if($isRegister)
                    <div class="field">
                        <label for="name">Full name</label>

                        <input
                            id="name"
                            name="name"
                            type="text"
                            maxlength="255"
                            autocomplete="name"
                            value="{{ old('name') }}"
                            aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                            required
                        >
                    </div>

                    <div class="field">
                        <label for="phone">Phone number</label>

                        <input
                            id="phone"
                            name="phone"
                            type="tel"
                            maxlength="50"
                            autocomplete="tel"
                            value="{{ old('phone') }}"
                            aria-invalid="{{ $errors->has('phone') ? 'true' : 'false' }}"
                            required
                        >
                    </div>
                @endif

                <div class="field">
                    <label for="email">Email address</label>

                    <input
                        id="email"
                        name="email"
                        type="email"
                        maxlength="255"
                        autocomplete="username"
                        value="{{ old('email') }}"
                        aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                        required
                    >
                </div>

                <div class="field">
                    <label for="password">Password</label>

                    <div class="password-wrap">
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="{{ $isRegister ? 'new-password' : 'current-password' }}"
                            aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                            @if($isRegister)
                                minlength="12"
                                maxlength="128"
                            @endif
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            data-password="password"
                            aria-controls="password"
                            aria-pressed="false"
                            aria-label="Show password"
                        >
                            Show
                        </button>
                    </div>

                    @if($isRegister)
                        <p class="help-text">
                            Use at least 12 characters.
                        </p>
                    @endif
                </div>

                @if($isRegister)
                    <div class="field">
                        <label for="password_confirmation">
                            Confirm password
                        </label>

                        <div class="password-wrap">
                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                minlength="12"
                                maxlength="128"
                                autocomplete="new-password"
                                required
                            >

                            <button
                                type="button"
                                class="password-toggle"
                                data-password="password_confirmation"
                                aria-controls="password_confirmation"
                                aria-pressed="false"
                                aria-label="Show confirmation password"
                            >
                                Show
                            </button>
                        </div>
                    </div>
                @endif

                <button
                    type="submit"
                    class="submit-button"
                    id="submitButton"
                >
                    {{ $isRegister
                        ? 'Create account'
                        : ($isStaff ? 'Login to dashboard' : 'Sign in') }}
                </button>
            </form>

            @if(!$isStaff)
                <p class="account-link">
                    {{ $isRegister
                        ? 'Already registered?'
                        : 'Need an account?' }}

                    <a
                        href="{{ $isRegister
                            ? route('guest.login')
                            : route('guest.register') }}"
                    >
                        {{ $isRegister
                            ? 'Sign in'
                            : 'Create a guest account' }}
                    </a>
                </p>

                @if($isRegister)
                    <p class="help-text registration-note">
                        Existing bookings made without an account are
                        not automatically added to this account.
                    </p>
                @endif
            @endif
        </section>
    </main>

    <script>
        (() => {
            const lampToggle = document.getElementById('lampToggle');
            const lampText = document.getElementById('lampText');
            const authCard = document.getElementById('authCard');
            const authForm = document.getElementById('authForm');
            const submitButton = document.getElementById('submitButton');

            const originalSubmitText =
                submitButton.textContent.trim();

            let lampOn = true;

            lampToggle.addEventListener('click', () => {
                lampOn = !lampOn;

                if (
                    !lampOn &&
                    authCard.contains(document.activeElement)
                ) {
                    lampToggle.focus();
                }

                document.body.classList.toggle('lamp-off', !lampOn);

                authCard.inert = !lampOn;

                authCard.setAttribute(
                    'aria-hidden',
                    String(!lampOn)
                );

                lampToggle.setAttribute(
                    'aria-pressed',
                    String(lampOn)
                );

                lampToggle.setAttribute(
                    'aria-label',
                    lampOn
                        ? 'Turn lamp off and hide login form'
                        : 'Turn lamp on and show login form'
                );

                lampText.textContent = lampOn
                    ? 'Tap to turn off'
                    : 'Tap to turn on';
            });

            document.querySelectorAll('[data-password]')
                .forEach(button => {
                    button.addEventListener('click', () => {
                        const input = document.getElementById(
                            button.dataset.password
                        );

                        const showPassword =
                            input.type === 'password';

                        input.type = showPassword
                            ? 'text'
                            : 'password';

                        button.textContent = showPassword
                            ? 'Hide'
                            : 'Show';

                        button.setAttribute(
                            'aria-pressed',
                            String(showPassword)
                        );

                        const fieldLabel =
                            input.id === 'password_confirmation'
                                ? 'confirmation password'
                                : 'password';

                        button.setAttribute(
                            'aria-label',
                            (showPassword ? 'Hide ' : 'Show ') +
                            fieldLabel
                        );
                    });
                });

            authForm.addEventListener('submit', () => {
                submitButton.disabled = true;
                submitButton.textContent = 'Please wait…';
            });

            window.addEventListener('pageshow', () => {
                submitButton.disabled = false;
                submitButton.textContent = originalSubmitText;
            });
        })();
    </script>
</body>
</html>