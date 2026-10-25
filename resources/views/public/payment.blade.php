<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Payment | McLovin Resort</title>

    <style>
        * {
            box-sizing: border-box;
        }

        :root {
            --gold: #cfae47;
            --gold-light: #f2de97;
            --silver: #d7dbe2;
            --muted: #b8bfca;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: #ffffff;
            background:
                radial-gradient(
                    circle at top left,
                    rgba(207,174,71,.08),
                    transparent 26%
                ),
                linear-gradient(180deg, #0d0d0f, #17181c);
            min-height: 100vh;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .container {
            width: min(1100px, calc(100% - 32px));
            margin: 0 auto;
        }

        .hero {
            padding: 28px 0 70px;
            background:
                linear-gradient(rgba(0,0,0,.30), rgba(0,0,0,.62)),
                linear-gradient(
                    135deg,
                    #111214 0%,
                    #1d2026 30%,
                    #343942 55%,
                    #15171c 75%,
                    #b08b2f 100%
                );
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            padding-bottom: 28px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand img {
            width: 52px;
            height: 52px;
            border-radius: 18px;
            object-fit: cover;
            border: 1px solid rgba(255,255,255,.55);
        }

        .brand h2 {
            margin: 0;
            font-size: 20px;
        }

        .brand p {
            margin: 4px 0 0;
            font-size: 12px;
            color: var(--muted);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .nav-links,
        .actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .nav-links a {
            padding: 12px 18px;
            border-radius: 999px;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.12);
            font-weight: 700;
        }

        .badge {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 999px;
            color: var(--gold-light);
            background: rgba(0,0,0,.24);
            border: 1px solid rgba(207,174,71,.30);
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        h1 {
            margin: 18px 0 10px;
            font-size: 44px;
        }

        .hero-description {
            margin: 0;
            color: var(--silver);
            font-size: 18px;
            line-height: 1.8;
        }

        .content-wrap {
            width: min(1100px, calc(100% - 32px));
            margin: -40px auto 60px;
            position: relative;
        }

        .card {
            padding: 28px;
            border-radius: 26px;
            background: linear-gradient(180deg, #1c1e24, #101116);
            border: 1px solid rgba(255,255,255,.08);
            box-shadow: 0 16px 40px rgba(0,0,0,.35);
        }

        .alert {
            padding: 16px;
            border-radius: 16px;
            margin-bottom: 20px;
            line-height: 1.7;
        }

        .alert-success {
            color: #86efac;
            background: rgba(34,197,94,.10);
            border: 1px solid rgba(34,197,94,.30);
        }

        .alert-error {
            color: #fca5a5;
            background: rgba(239,68,68,.10);
            border: 1px solid rgba(239,68,68,.30);
        }

        .alert-info {
            color: #93c5fd;
            background: rgba(59,130,246,.10);
            border: 1px solid rgba(59,130,246,.30);
        }

        .status-panel {
            text-align: center;
            padding: 30px 20px;
            margin-bottom: 28px;
            border-radius: 20px;
            background: rgba(34,197,94,.08);
            border: 1px solid rgba(34,197,94,.25);
        }

        .status-panel h2 {
            margin-top: 0;
            color: #86efac;
        }

        .status-panel p {
            color: var(--silver);
            line-height: 1.8;
        }

        .status-panel .actions {
            justify-content: center;
            margin-top: 24px;
        }

        .detail-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 24px;
        }

        .detail-item {
            padding: 18px;
            border-radius: 18px;
            background: linear-gradient(135deg, #1a1c22, #13151a);
            border: 1px solid rgba(255,255,255,.08);
        }

        .detail-item h4 {
            margin: 0 0 8px;
            color: var(--gold-light);
            font-size: 14px;
        }

        .detail-item p {
            margin: 0;
            color: var(--silver);
            overflow-wrap: anywhere;
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

        input,
        select,
        textarea {
            width: 100%;
            padding: 14px 16px;
            min-height: 52px;
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,.15);
            background: #000000;
            color: #ffffff;
            font: inherit;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .btn {
            display: inline-block;
            padding: 15px 22px;
            border-radius: 16px;
            border: none;
            cursor: pointer;
            font: inherit;
            font-weight: 800;
        }

        .btn-primary {
            background: var(--gold);
            color: #181818;
        }

        .btn-secondary {
            color: #ffffff;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.12);
        }

        .btn-success {
            background: #16a34a;
            color: #ffffff;
        }

        .actions {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .muted {
            color: var(--muted);
            line-height: 1.8;
        }

        :focus-visible {
            outline: 3px solid var(--gold-light);
            outline-offset: 3px;
        }

        @media (max-width: 900px) {
            .detail-list,
            .form-grid {
                grid-template-columns: 1fr;
            }

            h1 {
                font-size: 32px;
            }
        }
    </style>
</head>

<body>
    @php
        $checkIn = \Carbon\Carbon::parse($booking->check_in)->startOfDay();
        $checkOut = \Carbon\Carbon::parse($booking->check_out)->startOfDay();

        $validDates = $checkOut->greaterThan($checkIn);

        $nights = $validDates
            ? (int) $checkIn->diffInDays($checkOut)
            : null;

        $hasAgreedRate = $booking->agreed_rate !== null;

        $rateMinor = $hasAgreedRate
            ? (int) round((float) $booking->agreed_rate * 100)
            : null;

        $canCalculate = $validDates
            && $hasAgreedRate
            && $rateMinor >= 0;

        $totalMinor = $canCalculate
            ? $nights * $rateMinor
            : null;

        $roomPrice = $hasAgreedRate && $rateMinor >= 0
            ? $rateMinor / 100
            : null;

        $totalAmount = $canCalculate
            ? $totalMinor / 100
            : null;

        $paymentStatus = $booking->payment_status ?? 'pending';

        $acceptsSubmission = $booking->status === 'pending'
            && in_array($paymentStatus, ['pending', 'rejected'], true);

        $canSubmit = $acceptsSubmission
            && $canCalculate
            && $totalMinor > 0;

        $defaultPaymentAmount = $canCalculate
            ? number_format($totalAmount, 2, '.', '')
            : '';
    @endphp

    <header class="hero">
        <div class="container">
            <nav class="nav">
                <a href="{{ route('welcome') }}" class="brand">
                    <img src="/images/logo.jpg" alt="McLovin Resort Logo">

                    <div>
                        <h2>McLovin Resort</h2>
                        <p>Premium Direct Reservation</p>
                    </div>
                </a>

                <div class="nav-links">
                    <a href="{{ route('welcome') }}">Home</a>
                    <a href="{{ route('public.book') }}">Book Again</a>
                </div>
            </nav>

            <span class="badge">BOOKING PAYMENT</span>

            @if($paymentStatus === 'submitted')
                <h1>Payment Submitted</h1>
                <p class="hero-description">
                    Your payment details have been received.
                </p>
            @elseif($paymentStatus === 'verified')
                <h1>Payment Verified</h1>
                <p class="hero-description">
                    Our team has verified your payment.
                </p>
            @elseif($canSubmit)
                <h1>Complete Your Booking Payment</h1>
                <p class="hero-description">
                    Review your stay details and submit your payment receipt.
                </p>
            @else
                <h1>Booking Payment Status</h1>
                <p class="hero-description">
                    Review your booking details below.
                </p>
            @endif
        </div>
    </header>

    <main class="content-wrap">
        <div class="card">
            @if(session('warning'))
                <div class="alert alert-info" role="status">
                    {{ session('warning') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error" role="alert">
                    <ul style="margin:0; padding-left:18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($paymentStatus === 'submitted')
                <section class="status-panel" role="status">
                    <h2>Payment submitted successfully</h2>

                    <p>
                        We have received your payment details.
                        Our team will review them and let you know the result.
                    </p>

                    @if($booking->status === 'pending')
                        <p>Your booking is awaiting confirmation.</p>
                    @endif

                    <p>
                        You do not need to submit the payment again.
                        You can return to the home page or make another booking.
                    </p>

                    <div class="actions">
                        <a href="{{ route('welcome') }}" class="btn btn-primary">
                            Return to Home
                        </a>

                        <a href="{{ route('public.book') }}" class="btn btn-secondary">
                            Make Another Booking
                        </a>
                    </div>
                </section>

            @elseif($paymentStatus === 'verified')
                <section class="status-panel" role="status">
                    <h2>Payment verified</h2>

                    <p>Our team has verified your payment details.</p>

                    @if($booking->status === 'pending')
                        <p>Your booking is still awaiting staff confirmation.</p>
                    @endif

                    <div class="actions">
                        <a href="{{ route('welcome') }}" class="btn btn-primary">
                            Return to Home
                        </a>

                        <a href="{{ route('public.book') }}" class="btn btn-secondary">
                            Make Another Booking
                        </a>
                    </div>
                </section>

            @else
                @if(session('success'))
                    <div class="alert alert-success" role="status">
                        {{ session('success') }}
                    </div>
                @endif

                @if($paymentStatus === 'rejected')
                    <div class="alert alert-error">
                        Your payment submission was rejected.
                        Please contact reception for clarification.

                        @if($canSubmit)
                            You can submit corrected payment details below.
                        @endif
                    </div>
                @endif
            @endif

            @if(!$hasAgreedRate)
                <div class="alert alert-info">
                    The agreed room rate has not been recorded for this booking.
                    Please contact reception to confirm the amount before paying.
                </div>
            @endif

            @if(!$validDates)
                <div class="alert alert-error">
                    The booking dates require correction.
                    Please contact reception before making a payment.
                </div>
            @endif

            @if($hasAgreedRate && $rateMinor < 0)
                <div class="alert alert-error">
                    The recorded room rate requires correction.
                    Please contact reception before making a payment.
                </div>
            @endif

            <div class="detail-list">
                <div class="detail-item">
                    <h4>Booking ID</h4>
                    <p>#{{ $booking->id }}</p>
                </div>

                <div class="detail-item">
                    <h4>Guest</h4>
                    <p>{{ $booking->guest->full_name ?? 'N/A' }}</p>
                </div>

                <div class="detail-item">
                    <h4>Phone</h4>
                    <p>{{ $booking->guest->phone ?? 'N/A' }}</p>
                </div>

                <div class="detail-item">
                    <h4>Room</h4>
                    <p>
                        {{ $booking->room->number ?? 'N/A' }} -
                        {{ $booking->room->name ?? 'N/A' }}
                    </p>
                </div>

                <div class="detail-item">
                    <h4>Check In</h4>
                    <p>{{ $checkIn->format('d M Y') }}</p>
                </div>

                <div class="detail-item">
                    <h4>Check Out</h4>
                    <p>{{ $checkOut->format('d M Y') }}</p>
                </div>

                <div class="detail-item">
                    <h4>Agreed Room Price</h4>
                    <p>
                        @if($roomPrice !== null)
                            ৳ {{ number_format($roomPrice, 2) }} / night
                        @else
                            Not recorded
                        @endif
                    </p>
                </div>

                <div class="detail-item">
                    <h4>Total Nights</h4>
                    <p>{{ $nights ?? 'Requires correction' }}</p>
                </div>

                <div class="detail-item">
                    <h4>Total Amount</h4>
                    <p>
                        @if($canCalculate)
                            ৳ {{ number_format($totalAmount, 2) }}
                        @else
                            Requires staff review
                        @endif
                    </p>
                </div>

                <div class="detail-item">
                    <h4>Booking Status</h4>
                    <p>
                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                    </p>
                </div>

                <div class="detail-item">
                    <h4>Payment Status</h4>
                    <p>{{ ucfirst($paymentStatus) }}</p>
                </div>
            </div>

            @if($canSubmit)
                <div class="actions">
                    <button
                        type="button"
                        id="showPaymentButton"
                        class="btn btn-primary"
                        aria-controls="paymentForm"
                        aria-expanded="{{ $errors->any() ? 'true' : 'false' }}"
                        onclick="showPaymentForm()"
                    >
                        {{ $paymentStatus === 'rejected' ? 'Submit Again' : 'Pay Now' }}
                    </button>

                    <a href="{{ route('welcome') }}" class="btn btn-secondary">
                        Pay Later
                    </a>
                </div>

                <div
                    id="paymentForm"
                    @if(!$errors->any()) hidden @endif
                >
                    <h3>Send Payment To</h3>

                    <p><strong>bKash:</strong> 01XXXXXXXXX</p>
                    <p><strong>Nagad:</strong> 01XXXXXXXXX</p>
                    <p><strong>Rocket:</strong> 01XXXXXXXXX</p>

                    <div class="alert alert-info">
                        After sending payment, complete the form below
                        and upload your receipt.
                    </div>

                    <form
                        method="POST"
                        action="{{ route('public.book.payment.submit', $booking) }}"
                        enctype="multipart/form-data"
                    >
                        @csrf

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="payment_method">Payment Method</label>

                                <select
                                    id="payment_method"
                                    name="payment_method"
                                    required
                                >
                                    <option value="">Select Method</option>
                                    <option value="bkash" {{ old('payment_method') === 'bkash' ? 'selected' : '' }}>bKash</option>
                                    <option value="nagad" {{ old('payment_method') === 'nagad' ? 'selected' : '' }}>Nagad</option>
                                    <option value="rocket" {{ old('payment_method') === 'rocket' ? 'selected' : '' }}>Rocket</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="paid_amount">Paid Amount</label>

                                <input
                                    id="paid_amount"
                                    type="number"
                                    step="0.01"
                                    min="0.01"
                                    name="paid_amount"
                                    value="{{ old('paid_amount', $defaultPaymentAmount) }}"
                                    required
                                >
                            </div>

                            <div class="form-group full">
                                <label for="transaction_id">Transaction ID</label>

                                <input
                                    id="transaction_id"
                                    type="text"
                                    name="transaction_id"
                                    maxlength="100"
                                    value="{{ old('transaction_id') }}"
                                    placeholder="Enter transaction ID"
                                >
                            </div>

                            <div class="form-group full">
                                <label for="payment_description">
                                    Payment Details
                                </label>

                                <textarea
                                    id="payment_description"
                                    name="payment_description"
                                    rows="4"
                                    maxlength="2000"
                                    placeholder="Enter additional payment details"
                                >{{ old('payment_description') }}</textarea>
                            </div>

                            <div class="form-group full">
                                <label for="payment_receipt">Upload Receipt</label>

                                <input
                                    id="payment_receipt"
                                    type="file"
                                    name="payment_receipt"
                                    accept=".jpg,.jpeg,.png,.pdf"
                                    required
                                >

                                <small class="muted">
                                    Accepted formats: JPG, PNG, PDF.
                                    Maximum size: 2 MB.
                                </small>
                            </div>
                        </div>

                        <div class="actions">
                            <button type="submit" class="btn btn-success">
                                Submit Payment
                            </button>
                        </div>
                    </form>
                </div>

            @elseif(!in_array($paymentStatus, ['submitted', 'verified'], true))
                <div class="alert alert-info">
                    @if(!$canCalculate)
                        Payment submission is unavailable until reception
                        confirms the booking rate and dates.
                    @elseif($totalMinor === 0 && $acceptsSubmission)
                        No room payment is due for this booking.
                        Please contact reception for confirmation.
                    @else
                        This booking is not accepting payment submissions.
                        Please contact reception.
                    @endif
                </div>

                <div class="actions">
                    <a href="{{ route('welcome') }}" class="btn btn-primary">
                        Return to Home
                    </a>

                    <a href="{{ route('public.book') }}" class="btn btn-secondary">
                        Make Another Booking
                    </a>
                </div>
            @endif
        </div>
    </main>

    <script>
        function showPaymentForm() {
            const form = document.getElementById('paymentForm');
            const button = document.getElementById('showPaymentButton');

            if (!form || !button) {
                return;
            }

            form.hidden = false;
            button.setAttribute('aria-expanded', 'true');

            form.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    </script>
</body>
</html>