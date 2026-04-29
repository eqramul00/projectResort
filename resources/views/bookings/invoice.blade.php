<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Invoice</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #111827;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }

        .wrapper {
            padding: 28px;
            position: relative;
        }

        .watermark {
            position: absolute;
            top: 260px;
            left: 0;
            width: 100%;
            text-align: center;
            opacity: 0.05;
            z-index: 0;
        }

        .watermark img {
            width: 260px;
            height: auto;
        }

        .content {
            position: relative;
            z-index: 1;
        }

        .header {
            background: #071738;
            color: #ffffff;
            border-radius: 16px;
            padding: 22px 26px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            border: none;
            vertical-align: top;
            padding: 0;
        }

        .brand-cell {
            width: 55%;
        }

        .meta-cell {
            width: 45%;
            text-align: right;
        }

        .logo {
            height: 52px;
            margin-bottom: 8px;
        }

        .brand-title {
            margin: 0;
            font-size: 26px;
            font-weight: bold;
            line-height: 1.2;
        }

        .brand-subtitle {
            margin: 8px 0 0;
            font-size: 14px;
            color: #dbe4f0;
        }

        .invoice-number {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 12px 0;
        }

        .meta-line {
            margin: 0 0 10px 0;
            font-size: 13px;
            line-height: 1.5;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: bold;
            color: #ffffff;
        }

        .status-confirmed { background: #16a34a; }
        .status-pending { background: #f59e0b; }
        .status-cancelled { background: #dc2626; }
        .status-checked-in { background: #2563eb; }
        .status-checked-out { background: #6b7280; }

        .payment-pending { background: #f59e0b; }
        .payment-submitted { background: #2563eb; }
        .payment-verified { background: #16a34a; }
        .payment-rejected { background: #dc2626; }

        .section {
            margin-top: 26px;
        }

        .section-title {
            font-size: 17px;
            font-weight: bold;
            margin: 0 0 10px 0;
            color: #071738;
            padding-bottom: 6px;
            border-bottom: 1px solid #dbe2ea;
        }

        table.info-table {
            width: 100%;
            border-collapse: collapse;
        }

        table.info-table th,
        table.info-table td {
            border: 1px solid #cfd6de;
            padding: 10px 14px;
            text-align: left;
            vertical-align: middle;
        }

        table.info-table th {
            width: 35%;
            background: #f3f4f6;
            font-weight: bold;
            color: #111827;
        }

        .summary-box {
            width: 360px;
            margin-left: auto;
            margin-top: 24px;
        }

        .summary-box table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-box th,
        .summary-box td {
            border: 1px solid #cfd6de;
            padding: 10px 14px;
            text-align: left;
        }

        .summary-box th {
            background: #071738;
            color: #ffffff;
            width: 50%;
        }

        .summary-total {
            font-size: 16px;
            font-weight: bold;
        }

        .summary-due {
            font-size: 16px;
            font-weight: bold;
            color: #dc2626;
        }

        .note-box {
            margin-top: 24px;
            padding: 14px 16px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            color: #475569;
            line-height: 1.6;
        }

        .footer {
            margin-top: 28px;
            text-align: center;
            font-size: 11px;
            color: #6b7280;
            line-height: 1.8;
        }
    </style>
</head>
<body>
    @php
        $logoBase64 = null;

        $logoSources = [
            public_path('images/logo.jpg'),
            base_path('htdocs/images/logo.jpg'),
            'https://eqramulhasan.gt.tc/images/logo.jpg',
        ];

        foreach ($logoSources as $source) {
            try {
                if (str_starts_with($source, 'http')) {
                    $imageData = @file_get_contents($source);
                    if ($imageData !== false) {
                        $logoBase64 = 'data:image/jpeg;base64,' . base64_encode($imageData);
                        break;
                    }
                } else {
                    if (file_exists($source)) {
                        $imageData = @file_get_contents($source);
                        if ($imageData !== false) {
                            $extension = pathinfo($source, PATHINFO_EXTENSION) ?: 'jpg';
                            $logoBase64 = 'data:image/' . $extension . ';base64,' . base64_encode($imageData);
                            break;
                        }
                    }
                }
            } catch (\Throwable $e) {
                // ignore and try next source
            }
        }

        $checkIn = \Carbon\Carbon::parse($booking->check_in);
        $checkOut = \Carbon\Carbon::parse($booking->check_out);

        $nights = $checkIn->diffInDays($checkOut);
        $nights = $nights > 0 ? $nights : 1;

        $roomPrice = $booking->room->price ?? 0;
        $total = $nights * $roomPrice;
        $paidAmount = $booking->paid_amount ?? 0;
        $dueAmount = max($total - $paidAmount, 0);

        $statusClass = match($booking->status) {
            'confirmed' => 'status-confirmed',
            'pending' => 'status-pending',
            'cancelled' => 'status-cancelled',
            'checked_in' => 'status-checked-in',
            'checked_out' => 'status-checked-out',
            default => 'status-pending',
        };

        $paymentStatus = $booking->payment_status ?? 'pending';

        $paymentClass = match($paymentStatus) {
            'verified' => 'payment-verified',
            'submitted' => 'payment-submitted',
            'rejected' => 'payment-rejected',
            default => 'payment-pending',
        };
    @endphp

    <div class="wrapper">
        @if($logoBase64)
            <div class="watermark">
                <img src="{{ $logoBase64 }}" alt="Watermark">
            </div>
        @endif

        <div class="content">
            <div class="header">
                <table class="header-table">
                    <tr>
                        <td class="brand-cell">
                            @if($logoBase64)
                                <img src="{{ $logoBase64 }}" alt="Resort Sajek Logo" class="logo">
                            @endif

                            <h1 class="brand-title">Resort Sajek</h1>
                            <p class="brand-subtitle">Booking Invoice</p>
                        </td>

                        <td class="meta-cell">
                            <p class="invoice-number">Invoice #{{ $booking->id }}</p>
                            <p class="meta-line">Issue Date: {{ now()->format('d M Y') }}</p>
                            <p class="meta-line">
                                Booking Status:
                                <span class="status-badge {{ $statusClass }}">
                                    {{ ucwords(str_replace('_', ' ', $booking->status)) }}
                                </span>
                            </p>
                            <p class="meta-line">
                                Payment Status:
                                <span class="status-badge {{ $paymentClass }}">
                                    {{ ucwords(str_replace('_', ' ', $paymentStatus)) }}
                                </span>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <h2 class="section-title">Booking Information</h2>
                <table class="info-table">
                    <tr>
                        <th>Booking ID</th>
                        <td>#{{ $booking->id }}</td>
                    </tr>
                    <tr>
                        <th>Check In</th>
                        <td>{{ $checkIn->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Check Out</th>
                        <td>{{ $checkOut->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Total Nights</th>
                        <td>{{ $nights }}</td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <h2 class="section-title">Guest Information</h2>
                <table class="info-table">
                    <tr>
                        <th>Guest Name</th>
                        <td>{{ $booking->guest->full_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $booking->guest->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $booking->guest->email ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <h2 class="section-title">Room Information</h2>
                <table class="info-table">
                    <tr>
                        <th>Room</th>
                        <td>Room {{ $booking->room->number ?? '-' }} - {{ $booking->room->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Room Type</th>
                        <td>{{ $booking->room->type ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Price Per Night</th>
                        <td>BDT {{ number_format($roomPrice, 2) }}</td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <h2 class="section-title">Payment Information</h2>
                <table class="info-table">
                    <tr>
                        <th>Payment Method</th>
                        <td>{{ $booking->payment_method ? ucfirst($booking->payment_method) : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Paid Amount</th>
                        <td>BDT {{ number_format($paidAmount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Due Amount</th>
                        <td>BDT {{ number_format($dueAmount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Transaction ID</th>
                        <td>{{ $booking->transaction_id ?: '-' }}</td>
                    </tr>
                    <tr>
                        <th>Payment Status</th>
                        <td>{{ ucwords(str_replace('_', ' ', $paymentStatus)) }}</td>
                    </tr>
                    <tr>
                        <th>Payment Details</th>
                        <td>{{ $booking->payment_description ?: '-' }}</td>
                    </tr>
                </table>
            </div>

            <div class="summary-box">
                <table>
                    <tr>
                        <th>Nights</th>
                        <td>{{ $nights }}</td>
                    </tr>
                    <tr>
                        <th>Rate / Night</th>
                        <td>BDT {{ number_format($roomPrice, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Total Amount</th>
                        <td class="summary-total">BDT {{ number_format($total, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Paid</th>
                        <td>BDT {{ number_format($paidAmount, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Due</th>
                        <td class="summary-due">BDT {{ number_format($dueAmount, 2) }}</td>
                    </tr>
                </table>
            </div>

            <div class="note-box">
                Thank you for choosing <strong>Resort Sajek</strong>. Please keep this invoice for your records.
                @if($dueAmount > 0)
                    The remaining due amount is <strong>BDT {{ number_format($dueAmount, 2) }}</strong>.
                @endif
                For any support or booking questions, contact our front desk.
            </div>

            <div class="footer">
                <strong>Resort Sajek</strong><br>
                Sajek Valley, Rangamati, Bangladesh<br>
                © {{ date('Y') }} Resort Sajek. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>