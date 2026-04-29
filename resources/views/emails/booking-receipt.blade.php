<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Receipt</title>
</head>
<body style="margin:0;padding:0;background:#f6f8fb;font-family:Arial,Helvetica,sans-serif;color:#111827;">

<div style="max-width:700px;margin:40px auto;background:#ffffff;border:1px solid #e5e7eb;border-radius:18px;overflow:hidden;">

    @php
        // Booking status color
        $statusColor = '#16a34a';
        if ($booking->status == 'pending') {
            $statusColor = '#f59e0b';
        } elseif ($booking->status == 'cancelled') {
            $statusColor = '#dc2626';
        } elseif ($booking->status == 'checked_in') {
            $statusColor = '#2563eb';
        } elseif ($booking->status == 'checked_out') {
            $statusColor = '#6b7280';
        }

        // Payment status color
        $paymentStatus = $booking->payment_status ? $booking->payment_status : 'pending';

        $paymentColor = '#f59e0b';
        if ($paymentStatus == 'verified') {
            $paymentColor = '#16a34a';
        } elseif ($paymentStatus == 'submitted') {
            $paymentColor = '#2563eb';
        } elseif ($paymentStatus == 'rejected') {
            $paymentColor = '#dc2626';
        }

        // Inline styles (fixes red line issue)
        $statusStyle = "background:$statusColor;padding:5px 10px;border-radius:6px;color:#fff;";
        $paymentStyle = "background:$paymentColor;padding:5px 10px;border-radius:6px;color:#fff;";
    @endphp

    <!-- HEADER -->
    <div style="background:#071738;color:#ffffff;padding:24px 28px;">
        <table style="width:100%;">
            <tr>
                <td>
                    <img src="https://eqramulhasan.gt.tc/images/logo.jpg" style="height:56px;margin-bottom:10px;">
                    <h1 style="margin:0;font-size:28px;">Resort Sajek</h1>
                    <p style="margin:8px 0 0;color:#dbe4f0;">Booking Receipt</p>
                </td>

                <td style="text-align:right;">
                    <p style="font-weight:bold;">Receipt #{{ $booking->id }}</p>
                    <p>Issue Date: {{ now()->format('d M Y') }}</p>

                    <p>
                        Booking:
                        <span style="{{ $statusStyle }}">
                            {{ ucwords(str_replace('_',' ',$booking->status)) }}
                        </span>
                    </p>

                    <p>
                        Payment:
                        <span style="{{ $paymentStyle }}">
                            {{ ucwords(str_replace('_',' ',$paymentStatus)) }}
                        </span>
                    </p>
                </td>
            </tr>
        </table>
    </div>

    <!-- BODY -->
    <div style="padding:28px;">
        <p>Hello <strong>{{ $booking->guest->full_name ?? 'Guest' }}</strong>,</p>

        <p style="color:#334155;">
            Your booking request has been received. We will confirm your booking soon after verifying payment.
        </p>

        @php
            $checkIn = \Carbon\Carbon::parse($booking->check_in);
            $checkOut = \Carbon\Carbon::parse($booking->check_out);
            $nights = $checkIn->diffInDays($checkOut);
            $nights = $nights > 0 ? $nights : 1;

            $roomPrice = $booking->room->price ?? 0;
            $total = $nights * $roomPrice;
            $paid = $booking->paid_amount ?? 0;
            $due = $total - $paid;
            if ($due < 0) $due = 0;
        @endphp

        <table style="width:100%;border-collapse:collapse;font-size:14px;">
            <tr>
                <td style="padding:10px;border:1px solid #ddd;">Booking ID</td>
                <td style="padding:10px;border:1px solid #ddd;">#{{ $booking->id }}</td>
            </tr>

            <tr>
                <td style="padding:10px;border:1px solid #ddd;">Room</td>
                <td style="padding:10px;border:1px solid #ddd;">
                    {{ $booking->room->number ?? '-' }} - {{ $booking->room->name ?? '-' }}
                </td>
            </tr>

            <tr>
                <td style="padding:10px;border:1px solid #ddd;">Check In</td>
                <td style="padding:10px;border:1px solid #ddd;">
                    {{ $checkIn->format('d M Y') }}
                </td>
            </tr>

            <tr>
                <td style="padding:10px;border:1px solid #ddd;">Check Out</td>
                <td style="padding:10px;border:1px solid #ddd;">
                    {{ $checkOut->format('d M Y') }}
                </td>
            </tr>

            <tr>
                <td style="padding:10px;border:1px solid #ddd;">Nights</td>
                <td style="padding:10px;border:1px solid #ddd;">{{ $nights }}</td>
            </tr>

            <tr>
                <td style="padding:10px;border:1px solid #ddd;">Price / Night</td>
                <td style="padding:10px;border:1px solid #ddd;">
                    BDT {{ number_format($roomPrice,2) }}
                </td>
            </tr>

            <tr>
                <td style="padding:10px;border:1px solid #ddd;">Total</td>
                <td style="padding:10px;border:1px solid #ddd;font-weight:bold;">
                    BDT {{ number_format($total,2) }}
                </td>
            </tr>

            <tr>
                <td style="padding:10px;border:1px solid #ddd;">Paid</td>
                <td style="padding:10px;border:1px solid #ddd;">
                    BDT {{ number_format($paid,2) }}
                </td>
            </tr>

            <tr>
                <td style="padding:10px;border:1px solid #ddd;">Due</td>
                <td style="padding:10px;border:1px solid #ddd;color:red;">
                    BDT {{ number_format($due,2) }}
                </td>
            </tr>

            <tr>
                <td style="padding:10px;border:1px solid #ddd;">Payment Method</td>
                <td style="padding:10px;border:1px solid #ddd;">
                    {{ $booking->payment_method ? ucfirst($booking->payment_method) : 'N/A' }}
                </td>
            </tr>

            <tr>
                <td style="padding:10px;border:1px solid #ddd;">Transaction ID</td>
                <td style="padding:10px;border:1px solid #ddd;">
                    {{ $booking->transaction_id ?? 'N/A' }}
                </td>
            </tr>
        </table>

        <p style="margin-top:20px;color:#475569;">
            Thank you for choosing <strong>Resort Sajek</strong>. We look forward to welcoming you.
        </p>
    </div>

    <!-- FOOTER -->
    <div style="background:#f8fafc;padding:18px;text-align:center;">
        <p style="margin:0;font-size:13px;color:#475569;">
            Resort Sajek<br>
            Sajek Valley, Rangamati, Bangladesh
        </p>
        <p style="margin:8px 0 0;font-size:12px;color:#94a3b8;">
            © {{ date('Y') }} Resort Sajek
        </p>
    </div>

</div>
</body>
</html>