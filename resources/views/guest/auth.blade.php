<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        {{ $register ? 'Guest Registration' : 'Guest Login' }} | McLovin Resort
    </title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 32px 16px;
            min-height: 100vh;
            background: linear-gradient(135deg, #0d0d0f, #20232b);
            color: #ffffff;
            font-family: Arial, Helvetica, sans-serif;
        }

        .card {
            max-width: 480px;
            margin: 40px auto;
            padding: 32px;
            background: #17191f;
            border: 1px solid #34363e;
            border-radius: 24px;
        }

        h1 { margin-top: 0; }

        p { color: #cbd5e1; line-height: 1.6; }

        a { color: #f2de97; }

        .field { margin-bottom: 18px; }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 14px;
            border: 1px solid #484b55;
            border-radius: 12px;
            background: #0d0d0f;
            color: #ffffff;
            font: inherit;
        }

        button {
            width: 100%;
            padding: 15px;
            border: 0;
            border-radius: 12px;
            background: #cfae47;
            color: #181818;
            font: inherit;
            font-weight: bold;
            cursor: pointer;
        }

        .alert {
            padding: 14px;
            margin-bottom: 20px;
            border-radius: 12px;
            line-height: 1.6;
        }

        .error {
            background: #451a1a;
            color: #fecaca;
        }

        .success {
            background: #123223;
            color: #bbf7d0;
        }

        :focus-visible {
            outline: 3px solid #f2de97;
            outline-offset: 3px;
        }
    </style>
</head>
<body>
    <main class="card">
        <p><a href="{{ route('welcome') }}">Return to Home</a></p>

        <h1>{{ $register ? 'Create Guest Account' : 'Guest Login' }}</h1>

        <p>
            {{ $register
                ? 'Create an account to manage your new bookings.'
                : 'Sign in to view your bookings and payment status.' }}
        </p>

        @if(session('success'))
            <div class="alert success" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert error" role="alert">
                <ul style="margin:0; padding-left:20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            method="POST"
            action="{{ $register ? route('guest.register.store') : route('guest.login.attempt') }}"
        >
            @csrf

            @if($register)
                <div class="field">
                    <label for="name">Full Name</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        maxlength="255"
                        autocomplete="name"
                        value="{{ old('name') }}"
                        required
                    >
                </div>

                <div class="field">
                    <label for="phone">Phone Number</label>
                    <input
                        id="phone"
                        name="phone"
                        type="tel"
                        maxlength="50"
                        autocomplete="tel"
                        value="{{ old('phone') }}"
                        required
                    >
                </div>
            @endif

            <div class="field">
                <label for="email">Email Address</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    maxlength="255"
                    autocomplete="email"
                    value="{{ old('email') }}"
                    required
                >
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    autocomplete="{{ $register ? 'new-password' : 'current-password' }}"
                    @if($register) minlength="12" maxlength="128" @endif
                    required
                >

                @if($register)
                    <p>Use at least 12 characters.</p>
                @endif
            </div>

            @if($register)
                <div class="field">
                    <label for="password_confirmation">Confirm Password</label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        minlength="12"
                        maxlength="128"
                        autocomplete="new-password"
                        required
                    >
                </div>
            @endif

            <button type="submit">
                {{ $register ? 'Create Account' : 'Log In' }}
            </button>
        </form>

        @if($register)
            <p>
                Already registered?
                <a href="{{ route('guest.login') }}">Log in</a>
            </p>

            <p>
                Existing bookings made without an account are not
                automatically added to this account.
            </p>
        @else
            <p>
                Need an account?
                <a href="{{ route('guest.register') }}">Register</a>
            </p>
        @endif
    </main>
</body>
</html>