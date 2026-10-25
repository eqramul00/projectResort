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
            margin: 0 0 12px;
        }

        .meta-line {
            margin: 0 0 10px;
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
            margin: 0 0 10px;
            color: #071738;
            padding-bottom: 6px;
            border-bottom: 1px solid #dbe2ea;
            page-break-after: avoid;
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
            overflow-wrap: break-word;
        }

        table.info-table th {
            width: 35%;
            background: #f3f4f6;
            font-weight: bold;
            color: #111827;
        }

        table.info-table thead {
            display: table-header-group;
        }

        table.info-table tr {
            page-break-inside: avoid;
        }

        .summary-box {
            width: 360px;
            margin-left: auto;
            margin-top: 24px;
            page-break-inside: avoid;
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

        .warning-box {
            margin-top: 20px;
            padding: 14px 16px;
            background: #fffbeb;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            color: #92400e;
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
        /** @var \App\Models\Booking $booking */

        $logoBase64 = null;

        $logoSources = [
            public_path('images/logo.jpg'),
            base_path('htdocs/images/logo.jpg'),
        ];

        foreach ($logoSources as $source) {
            if (is_file($source) && is_readable($source)) {
                $imageData = @file_get_contents($source);

                if ($imageData !== false) {
                    $logoBase64 = 'data:image/jpeg;base64,'
                        . base64_encode($imageData);

                    break;
                }
            }
        }

        $timezone = config('hotel.timezone', 'Europe/London');

        $checkIn = \Illuminate\Support\Carbon::parse(
            $booking->check_in,
            $timezone
        )->startOfDay();

        $checkOut = \Illuminate\Support\Carbon::parse(
            $booking->check_out,
            $timezone
        )->startOfDay();

        $validDates = $checkOut->greaterThan($checkIn);

        $hasAgreedRate = $booking->agreed_rate !== null;

        $validRate = $hasAgreedRate
            && is_numeric($booking->agreed_rate)
            && (float) $booking->agreed_rate >= 0;

        $bill = app(\App\Services\StayManagement::class)
            ->summary($booking);

        $canCalculate = $bill['priced'];
        $nights = $validDates ? $bill['nights'] : null;
        $rateMinor = $validRate ? $bill['rate'] : null;

        $scheduledMinor = $bill['scheduled'];
        $lateMinor = $bill['late_charge'];
        $totalMinor = $bill['total'];

        $originalVerifiedMinor = $bill['original_paid'];
        $additionalVerifiedMinor = $bill['additional_paid'];
        $verifiedMinor = $bill['paid'];

        $dueMinor = $bill['balance'];
        $creditMinor = $bill['credit'];

        $paymentStatus = $booking->payment_status ?? 'pending';

        $recordedMinor = max(
            0,
            (int) round((float) ($booking->paid_amount ?? 0) * 100)
        );

        $stayPayments = $booking->stayPayments()
            ->orderBy('id')
            ->get();

        $hasStayPayments = $stayPayments->isNotEmpty();

        $departedAt = $booking->actual_departed_at !== null
            ? \Illuminate\Support\Carbon::parse(
                $booking->actual_departed_at
            )->setTimezone($timezone)
            : null;

        $completedAt = $booking->checkout_completed_at !== null
            ? \Illuminate\Support\Carbon::parse(
                $booking->checkout_completed_at
            )->setTimezone($timezone)
            : null;

        $provisionalBill = $booking->status === 'checked_in'
            && $departedAt === null;

        $unknownDeparture = $booking->status === 'checked_out'
            && $departedAt === null;

        $bookingStatusLabel = $booking->status === 'checked_in'
            && $departedAt !== null
                ? 'Departed — financial checkout pending'
                : ucwords(str_replace('_', ' ', $booking->status));

        $statusClass = match ($booking->status) {
            'confirmed' => 'status-confirmed',
            'pending' => 'status-pending',
            'cancelled' => 'status-cancelled',
            'checked_in' => 'status-checked-in',
            'checked_out' => 'status-checked-out',
            default => 'status-pending',
        };

        $paymentClass = match ($paymentStatus) {
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
                                <img
                                    src="{{ $logoBase64 }}"
                                    alt="Resort Sajek Logo"
                                    class="logo"
                                >
                            @endif

                            <h1 class="brand-title">Resort Sajek</h1>
                            <p class="brand-subtitle">Booking Invoice</p>
                        </td>

                        <td class="meta-cell">
                            <p class="invoice-number">
                                Invoice #{{ $booking->id }}
                            </p>

                            <p class="meta-line">
                                Generated:
                                {{ \Illuminate\Support\Carbon::now($timezone)->format('d M Y, H:i T') }}
                            </p>

                            <p class="meta-line">
                                Booking Status:
                                <span class="status-badge {{ $statusClass }}">
                                    {{ $bookingStatusLabel }}
                                </span>
                            </p>

                            <p class="meta-line">
                                Original Payment:
                                <span class="status-badge {{ $paymentClass }}">
                                    {{ ucwords(str_replace('_', ' ', $paymentStatus)) }}
                                </span>
                            </p>
                        </td>
                    </tr>
                </table>
            </div>

            @if(!$hasAgreedRate)
                <div class="warning-box">
                    <strong>Original booking rate not recorded.</strong>
                    Staff must check the original booking records before
                    issuing a final total. The current room price has
                    not been used as a replacement.
                </div>
            @endif

            @if($hasAgreedRate && !$validRate)
                <div class="warning-box">
                    <strong>The recorded rate is invalid.</strong>
                    Staff must review it before issuing a final total.
                </div>
            @endif

            @if(!$validDates)
                <div class="warning-box">
                    <strong>Booking dates require correction.</strong>
                    Check-out must be after check-in. Totals cannot be
                    calculated until the dates are corrected.
                </div>
            @endif

            @if($booking->status === 'cancelled')
                <div class="warning-box">
                    This booking is cancelled. Any applicable cancellation
                    charges or refunds must be reviewed by staff.
                    The figures below describe the recorded stay and payments.
                </div>
            @endif

            @if($provisionalBill)
                <div class="warning-box">
                    This bill is provisional while the guest is still staying.
                    Checkout is due
                    <strong>{{ $bill['deadline']->format('d M Y, H:i T') }}</strong>.
                    Each started extra day after the deadline costs one agreed
                    nightly rate. Charges may change until actual departure
                    or an approved extension is recorded.
                </div>
            @endif

            @if($unknownDeparture)
                <div class="warning-box">
                    This historical booking has no recorded actual departure
                    time. No late charges have been inferred using today's
                    date. Any historical late charges require review
                    against the original records.
                </div>
            @endif

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
                        <th>Scheduled Check Out</th>
                        <td>{{ $checkOut->format('d M Y') }}</td>
                    </tr>

                    <tr>
                        <th>Scheduled Nights</th>
                        <td>{{ $nights ?? 'Requires correction' }}</td>
                    </tr>

                    <tr>
                        <th>Checkout Deadline</th>
                        <td>{{ $bill['deadline']->format('d M Y, H:i T') }}</td>
                    </tr>

                    <tr>
                        <th>Actual Departure</th>
                        <td>
                            {{ $departedAt ? $departedAt->format('d M Y, H:i T') : 'Not recorded' }}
                        </td>
                    </tr>

                    <tr>
                        <th>Financial Checkout Completed</th>
                        <td>
                            {{ $completedAt ? $completedAt->format('d M Y, H:i T') : 'Not completed' }}
                        </td>
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
                        <td>
                            Room {{ $booking->room->number ?? '-' }}
                            - {{ $booking->room->name ?? '-' }}
                        </td>
                    </tr>

                    <tr>
                        <th>Room Type</th>
                        <td>{{ $booking->room->type ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th>Agreed Price Per Night</th>
                        <td>
                            @if($validRate)
                                BDT {{ number_format($rateMinor / 100, 2) }}
                            @else
                                Requires review
                            @endif
                        </td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <h2 class="section-title">Payment Information</h2>

                <table class="info-table">
                    <tr>
                        <th>Original Payment Method</th>
                        <td>
                            {{ $booking->payment_method ? ucfirst($booking->payment_method) : '-' }}
                        </td>
                    </tr>

                    <tr>
                        <th>Original Recorded Payment</th>
                        <td>
                            BDT {{ number_format($recordedMinor / 100, 2) }}
                        </td>
                    </tr>

                    <tr>
                        <th>Original Verified Payment</th>
                        <td>
                            BDT {{ number_format($originalVerifiedMinor / 100, 2) }}
                        </td>
                    </tr>

                    <tr>
                        <th>Additional Verified Payments</th>
                        <td>
                            BDT {{ number_format($additionalVerifiedMinor / 100, 2) }}
                        </td>
                    </tr>

                    <tr>
                        <th>Total Verified Payments</th>
                        <td>
                            BDT {{ number_format($verifiedMinor / 100, 2) }}
                        </td>
                    </tr>

                    <tr>
                        <th>Remaining Balance</th>
                        <td>
                            @if($canCalculate)
                                BDT {{ number_format($dueMinor / 100, 2) }}
                            @else
                                Requires rate/date review
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Original Transaction ID</th>
                        <td>{{ $booking->transaction_id ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>Original Payment Status</th>
                        <td>
                            {{ ucwords(str_replace('_', ' ', $paymentStatus)) }}
                        </td>
                    </tr>

                    <tr>
                        <th>Payment Details</th>
                        <td>{{ $booking->payment_description ?: '-' }}</td>
                    </tr>
                </table>
            </div>

            @if($hasStayPayments)
                <div class="section">
                    <h2 class="section-title">Additional Stay Payments</h2>

                    <table class="info-table">
                        <thead>
                            <tr>
                                <td><strong>Type / Details</strong></td>
                                <td><strong>Amount</strong></td>
                                <td><strong>Method / Reference</strong></td>
                                <td><strong>Verification</strong></td>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($stayPayments as $payment)
                                <tr>
                                    <td>
                                        {{ ucfirst(str_replace('_', ' ', $payment->type)) }}

                                        @if($payment->type === 'extension')
                                            <br>
                                            {{ $payment->extra_nights }} extra night(s)

                                            @if($payment->previous_check_out && $payment->new_check_out)
                                                <br>

                                                {{ \Illuminate\Support\Carbon::parse($payment->previous_check_out)->format('d M Y') }}
                                                to
                                                {{ \Illuminate\Support\Carbon::parse($payment->new_check_out)->format('d M Y') }}
                                            @endif
                                        @endif
                                    </td>

                                    <td>
                                        BDT {{ number_format((float) $payment->amount, 2) }}
                                    </td>

                                    <td>
                                        {{ ucfirst($payment->payment_method) }}
                                        <br>
                                        {{ $payment->transaction_id ?: '-' }}
                                    </td>

                                    <td>
                                        @if($payment->verified_at)
                                            Verified
                                            <br>

                                            {{ \Illuminate\Support\Carbon::parse($payment->verified_at)->setTimezone($timezone)->format('d M Y, H:i T') }}
                                        @else
                                            Not verified
                                            <br>
                                            Excluded from paid total
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="summary-box">
                <table>
                    <tr>
                        <th>Scheduled Nights</th>
                        <td>{{ $nights ?? 'Requires correction' }}</td>
                    </tr>

                    <tr>
                        <th>Agreed Rate / Night</th>
                        <td>
                            @if($validRate)
                                BDT {{ number_format($rateMinor / 100, 2) }}
                            @else
                                Requires review
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Scheduled Stay</th>
                        <td>
                            @if($canCalculate)
                                BDT {{ number_format($scheduledMinor / 100, 2) }}
                            @else
                                Requires review
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Late Stay Days</th>
                        <td>{{ $bill['late_nights'] }}</td>
                    </tr>

                    <tr>
                        <th>Late Stay Charges</th>
                        <td>
                            @if($canCalculate)
                                BDT {{ number_format($lateMinor / 100, 2) }}
                            @else
                                Requires review
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Total Amount</th>
                        <td class="summary-total">
                            @if($canCalculate)
                                BDT {{ number_format($totalMinor / 100, 2) }}
                            @else
                                Requires review
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Total Verified Paid</th>
                        <td>
                            BDT {{ number_format($verifiedMinor / 100, 2) }}
                        </td>
                    </tr>

                    <tr>
                        <th>Balance</th>
                        <td class="summary-due">
                            @if($canCalculate)
                                BDT {{ number_format($dueMinor / 100, 2) }}
                            @else
                                Requires review
                            @endif
                        </td>
                    </tr>

                    @if($canCalculate && $creditMinor > 0)
                        <tr>
                            <th>Overpayment</th>
                            <td>
                                BDT {{ number_format($creditMinor / 100, 2) }}
                                <br>
                                Staff review required
                            </td>
                        </tr>
                    @endif
                </table>
            </div>

            <div class="note-box">
                Thank you for choosing <strong>Resort Sajek</strong>.
                Please keep this invoice for your records.

                Approved extension nights are already included in the
                scheduled stay amount. Additional verified payments are
                deducted once from the total. Recording a late payment
                alone does not approve a stay extension.

                @if($paymentStatus === 'submitted')
                    Your original submitted payment is awaiting verification
                    and has not yet been deducted from the balance.
                @elseif($paymentStatus === 'rejected')
                    The original payment submission was rejected
                    and has not been deducted from the balance.
                @endif

                @if($canCalculate && $dueMinor > 0 && $booking->status !== 'cancelled')
                    The remaining balance is
                    <strong>BDT {{ number_format($dueMinor / 100, 2) }}</strong>.
                @endif

                @if(!$canCalculate)
                    Staff must review the missing or invalid rate and dates
                    before confirming the final amount.
                @endif

                For support or booking questions, contact our front desk.
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