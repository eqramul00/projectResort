<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment | McLovin Resort</title>

    <style>
        * { box-sizing: border-box; }

        :root {
            --bg: #0d0d0f;
            --bg-2: #17181c;
            --white: #ffffff;
            --silver: #d7dbe2;
            --muted: #b8bfca;
            --gold: #cfae47;
            --gold-2: #f2de97;
            --line: rgba(255,255,255,.10);
            --line-gold: rgba(207,174,71,.30);
            --shadow: 0 16px 40px rgba(0, 0, 0, 0.35);
            --radius: 26px;
            --max: 1100px;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: var(--white);
            background:
                radial-gradient(circle at top left, rgba(207,174,71,.08), transparent 26%),
                radial-gradient(circle at top right, rgba(255,255,255,.04), transparent 22%),
                linear-gradient(180deg, var(--bg) 0%, var(--bg-2) 100%);
        }

        a { text-decoration: none; color: inherit; }

        .container {
            width: min(var(--max), calc(100% - 32px));
            margin: 0 auto;
        }

        .hero {
            padding: 28px 0 70px;
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

        .brand-mark {
            width: 52px;
            height: 52px;
            border-radius: 18px;
            overflow: hidden;
            background:
                linear-gradient(180deg, rgba(255,255,255,0.30) 0%, rgba(255,255,255,0.04) 30%, rgba(0,0,0,0.18) 100%),
                linear-gradient(135deg, #fff7db 0%, #cfae47 22%, #f4e3a4 40%, #8f6b18 65%, #f7ebc2 85%, #9f7c1f 100%);
            display: flex;
            align-items: center;
            justify-content: center;
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
        }

        .hero-head h1 {
            margin: 18px 0 10px;
            font-size: 46px;
            line-height: 1.06;
        }

        .hero-head p {
            color: rgba(255,255,255,0.82);
            font-size: 18px;
            line-height: 1.8;
            margin: 0;
        }

        .content-wrap {
            width: min(var(--max), calc(100% - 32px));
            margin: -40px auto 60px;
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

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            gap: 16px;
            padding: 24px 28px 0;
        }

        .card-body {
            padding: 28px;
        }

        .page-title-badge {
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

        .muted {
            color: var(--muted);
            line-height: 1.8;
        }

        .alert-success,
        .alert-error,
        .alert-info {
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

        .detail-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 24px;
        }

        .detail-item {
            border: 1px solid rgba(255,255,255,.08);
            background: linear-gradient(135deg, #1a1c22, #13151a);
            border-radius: 18px;
            padding: 18px;
        }

        .detail-item h4 {
            margin: 0 0 8px;
            color: var(--gold-2);
            font-size: 14px;
        }

        .detail-item p {
            margin: 0;
            color: var(--silver);
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

        input, select, textarea {
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

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .btn {
            display: inline-block;
            border: none;
            cursor: pointer;
            border-radius: 16px;
            padding: 15px 22px;
            font-weight: 800;
        }

        .btn-primary {
            background: var(--gold);
            color: #181818;
        }

        .btn-secondary {
            background: rgba(255,255,255,0.08);
            color: var(--white);
            border: 1px solid rgba(255,255,255,0.12);
        }

        .btn-success {
            background: #16a34a;
            color: white;
        }

        @media (max-width: 900px) {
            .detail-list,
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    @php
        $checkIn = \Carbon\Carbon::parse($booking->check_in);
        $checkOut = \Carbon\Carbon::parse($booking->check_out);
        $nights = $checkIn->diffInDays($checkOut);
        $roomPrice = $booking->room->price ?? 0;
        $totalAmount = $nights * $roomPrice;
    @endphp

    <section class="hero">
        <div class="container">
            <div class="nav">
                <a href="{{ route('welcome') }}" class="brand">
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
                    <a href="{{ route('public.book') }}">Book Again</a>
                </div>
            </div>

            <div class="hero-head">
                <span class="page-title-badge">ONLINE PAYMENT</span>
                <h1>Complete Your Booking Payment</h1>
                <p>Your booking has been received. Review your stay details below and submit your payment receipt.</p>
            </div>
        </div>
    </section>

    <div class="content-wrap">
        <div class="card">
            <div class="card-body">
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

                <div class="detail-list">
                    <div class="detail-item">
                        <h4>Booking ID</h4>
                        <p>#{{ $booking->id }}</p>
                    </div>

                    <div class="detail-item">
                        <h4>Guest</h4>
                        <p>{{ $booking->guest->full_name }}</p>
                    </div>

                    <div class="detail-item">
                        <h4>Phone</h4>
                        <p>{{ $booking->guest->phone }}</p>
                    </div>

                    <div class="detail-item">
                        <h4>Room</h4>
                        <p>{{ $booking->room->number }} - {{ $booking->room->name }}</p>
                    </div>

                    <div class="detail-item">
                        <h4>Check In</h4>
                        <p>{{ $booking->check_in }}</p>
                    </div>

                    <div class="detail-item">
                        <h4>Check Out</h4>
                        <p>{{ $booking->check_out }}</p>
                    </div>

                    <div class="detail-item">
                        <h4>Room Price</h4>
                        <p>৳ {{ number_format($roomPrice, 2) }} / night</p>
                    </div>

                    <div class="detail-item">
                        <h4>Total Nights</h4>
                        <p>{{ $nights }}</p>
                    </div>

                    <div class="detail-item">
                        <h4>Total Amount</h4>
                        <p style="font-size: 20px; font-weight: 700; color: #cfae47;">
                            ৳ {{ number_format($totalAmount, 2) }}
                        </p>
                    </div>
                </div>

                <div style="margin-bottom: 20px; display: flex; gap: 12px; flex-wrap: wrap;">
                    <button type="button" class="btn btn-primary" onclick="showPaymentForm()">Pay Now</button>
                    <a href="{{ route('welcome') }}" class="btn btn-secondary">Pay Later</a>
                </div>

                <div id="paymentForm" style="{{ session('success') ? 'display:none;' : ($errors->any() ? 'display:block;' : 'display:none;') }}">
                    <div class="card" style="padding: 20px;">
                        <h3 style="margin-bottom: 12px;">Send Payment To</h3>

                        <p><strong>bKash:</strong> 01XXXXXXXXX</p>
                        <p><strong>Nagad:</strong> 01XXXXXXXXX</p>
                        <p><strong>Rocket:</strong> 01XXXXXXXXX</p>

                        <div class="alert-info" style="margin-top: 16px;">
                            After sending payment, fill in the form below and upload your receipt.
                        </div>

                        <form method="POST"
                              action="{{ route('public.book.payment.submit', $booking) }}"
                              enctype="multipart/form-data"
                              style="margin-top: 20px;">
                            @csrf

                            <div class="form-grid">
                                <div class="form-group">
                                    <label>Payment Method</label>
                                    <select name="payment_method" required>
                                        <option value="">Select Method</option>
                                        <option value="bkash" {{ old('payment_method') == 'bkash' ? 'selected' : '' }}>bKash</option>
                                        <option value="nagad" {{ old('payment_method') == 'nagad' ? 'selected' : '' }}>Nagad</option>
                                        <option value="rocket" {{ old('payment_method') == 'rocket' ? 'selected' : '' }}>Rocket</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Paid Amount</label>
                                    <input type="number"
                                           step="0.01"
                                           name="paid_amount"
                                           value="{{ old('paid_amount', $totalAmount) }}"
                                           required>
                                </div>

                                <div class="form-group full">
                                    <label>Transaction ID</label>
                                    <input type="text"
                                           name="transaction_id"
                                           value="{{ old('transaction_id') }}"
                                           placeholder="Enter transaction ID">
                                </div>

                                <div class="form-group full">
                                    <label>Payment Details</label>
                                    <textarea name="payment_description"
                                              rows="4"
                                              placeholder="Example: Paid via bKash from 01XXXXXXXXX at 8:45 PM">{{ old('payment_description') }}</textarea>
                                </div>

                                <div class="form-group full">
                                    <label>Upload Receipt</label>
                                    <input type="file"
                                           name="payment_receipt"
                                           accept=".jpg,.jpeg,.png,.pdf"
                                           required>
                                    <small class="muted">Accepted formats: JPG, PNG, PDF</small>
                                </div>
                            </div>

                            <div style="margin-top: 20px;">
                                <button type="submit" class="btn btn-success">Submit Payment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showPaymentForm() {
            document.getElementById('paymentForm').style.display = 'block';
        }
    </script>
</body>
</html>