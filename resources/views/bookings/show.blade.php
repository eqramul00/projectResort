<x-app-layout>
    @php
        /** @var \App\Models\Booking $booking */

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

        $staff = auth()->user();

        $canManage = $staff->canManageBookings();
        $isAdmin = $staff->role === 'admin';

        $canEdit = $canManage
            && in_array(
                $booking->status,
                ['pending', 'confirmed'],
                true
            );

        $canHandleStay = $staff->hasAnyRole([
            'admin',
            'manager',
            'reception',
        ]);

        $bookingStatusLabel = $booking->status === 'checked_in'
            && $departedAt !== null
                ? 'Departed — financial checkout pending'
                : ucfirst(str_replace('_', ' ', $booking->status));
    @endphp

    <div class="card">
        <div class="card-header">
            <div>
                <span class="page-title-badge">BOOKING DETAILS</span>

                <h2>Booking #{{ $booking->id }}</h2>

                <p class="muted">
                    Full reservation information, payment details, and stay status.
                </p>
            </div>

            <div class="actions">
                @if($canEdit)
                    <a
                        href="{{ route('bookings.edit', $booking) }}"
                        class="btn btn-warning"
                    >
                        Edit
                    </a>
                @endif

                <a
                    href="{{ route('bookings.index') }}"
                    class="btn btn-secondary"
                >
                    Back
                </a>

                @if($booking->status === 'checked_in' && $canHandleStay)
                    <a
                        href="{{ route('stays.index', ['booking' => $booking->id]) }}"
                        class="btn btn-primary"
                    >
                        Stay Management
                    </a>
                @endif

                @if($isAdmin)
                    <a
                        href="{{ route('bookings.invoice', $booking) }}"
                        class="btn btn-dark"
                    >
                        Download Invoice
                    </a>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom:16px;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error" style="margin-bottom:16px;">
                {{ session('error') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning" style="margin-bottom:16px;">
                {{ session('warning') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error" style="margin-bottom:16px;">
                <ul style="margin:0; padding-left:18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(!$hasAgreedRate)
            <div class="alert alert-warning" style="margin-bottom:16px;">
                The original agreed rate has not been recorded.
                Check the original booking records before confirming
                the final bill. The current room price has not been
                used as a replacement.
            </div>
        @endif

        @if(!$validDates)
            <div class="alert alert-error" style="margin-bottom:16px;">
                The booking dates require correction.
                Check-out must be after check-in.
            </div>
        @endif

        @if($hasAgreedRate && !$validRate)
            <div class="alert alert-error" style="margin-bottom:16px;">
                The recorded agreed rate requires correction.
                Totals cannot be calculated.
            </div>
        @endif

        @if($booking->status === 'cancelled')
            <div class="alert alert-warning" style="margin-bottom:16px;">
                This booking is cancelled. Any cancellation charges
                or refunds require staff review. The amounts below
                describe the recorded stay and payments.
            </div>
        @endif

        @if($provisionalBill)
            <div class="alert alert-warning" style="margin-bottom:16px;">
                This bill is provisional while the guest is still staying.
                Checkout is due
                <strong>{{ $bill['deadline']->format('d M Y, H:i T') }}</strong>.
                After that deadline, each started extra day costs one
                agreed nightly rate. Use Stay Management to record
                extensions, extra payments or actual departure.
            </div>
        @endif

        @if($unknownDeparture)
            <div class="alert alert-warning" style="margin-bottom:16px;">
                The actual departure time is not recorded for this historical
                booking. No late charge has been inferred from today's date.
                Staff must review any historical late charges against the
                original records.
            </div>
        @endif

        <div class="detail-list">
            <div class="detail-item">
                <h4>Room</h4>
                <p>
                    {{ $booking->room->number ?? 'N/A' }} -
                    {{ $booking->room->name ?? 'N/A' }}
                </p>
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
                <h4>Email</h4>
                <p>{{ $booking->guest->email ?? 'N/A' }}</p>
            </div>

            <div class="detail-item">
                <h4>Status</h4>
                <p>
                    <span class="status status-{{ $booking->status }}">
                        {{ $bookingStatusLabel }}
                    </span>
                </p>
            </div>

            <div class="detail-item">
                <h4>Check In</h4>
                <p>{{ $checkIn->format('d M Y') }}</p>
            </div>

            <div class="detail-item">
                <h4>Scheduled Check Out</h4>
                <p>{{ $checkOut->format('d M Y') }}</p>
            </div>

            <div class="detail-item">
                <h4>Agreed Rate Per Night</h4>
                <p>
                    @if($validRate)
                        ৳ {{ number_format($rateMinor / 100, 2) }}
                    @else
                        Requires staff review
                    @endif
                </p>
            </div>

            <div class="detail-item">
                <h4>Scheduled Nights</h4>
                <p>{{ $nights ?? 'Requires correction' }}</p>
            </div>

            <div class="detail-item">
                <h4>Scheduled Stay Amount</h4>
                <p>
                    @if($canCalculate)
                        ৳ {{ number_format($scheduledMinor / 100, 2) }}
                    @else
                        Requires staff review
                    @endif
                </p>
            </div>

            <div class="detail-item">
                <h4>Extra Days After Checkout Deadline</h4>
                <p>{{ $bill['late_nights'] }}</p>
            </div>

            <div class="detail-item">
                <h4>Late Stay Charges</h4>
                <p>
                    @if($canCalculate)
                        ৳ {{ number_format($lateMinor / 100, 2) }}
                    @else
                        Requires staff review
                    @endif
                </p>
            </div>

            <div class="detail-item">
                <h4>Actual Departure</h4>
                <p>
                    {{ $departedAt ? $departedAt->format('d M Y, H:i T') : 'Not recorded' }}
                </p>
            </div>

            <div class="detail-item">
                <h4>Financial Checkout Completed</h4>
                <p>
                    {{ $completedAt ? $completedAt->format('d M Y, H:i T') : 'Not completed' }}
                </p>
            </div>

            <div class="detail-item">
                <h4>Total Amount</h4>
                <p>
                    @if($canCalculate)
                        ৳ {{ number_format($totalMinor / 100, 2) }}
                    @else
                        Requires staff review
                    @endif
                </p>
            </div>
        </div>

        <div class="card" style="margin-top:24px; padding:20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">
                <div>
                    <span class="page-title-badge">PAYMENT INFO</span>

                    <h3 style="margin:8px 0 4px;">
                        Payment Details
                    </h3>

                    <p class="muted" style="margin:0;">
                        Original payment details and verified payments for the whole stay.
                    </p>
                </div>

                <div class="actions">
                    @if($canManage && $booking->status !== 'cancelled' && !$hasStayPayments)
                        <a
                            href="{{ route('bookings.manual.payment', $booking) }}"
                            class="btn btn-primary"
                        >
                            Record Original Payment
                        </a>
                    @endif

                    @if($booking->payment_receipt)
                        <a
                            href="{{ asset('storage/' . $booking->payment_receipt) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn btn-secondary"
                        >
                            View Receipt
                        </a>
                    @endif
                </div>
            </div>

            @if($paymentStatus === 'submitted')
                <div class="alert alert-warning" style="margin-top:18px;">
                    The original submitted payment is awaiting verification.
                    It has not been deducted from the balance.
                </div>
            @elseif($paymentStatus === 'rejected')
                <div class="alert alert-error" style="margin-top:18px;">
                    The original payment submission was rejected.
                    It has not been deducted from the balance.
                </div>
            @endif

            @if($hasStayPayments && $booking->status === 'checked_in')
                <p class="muted" style="margin-top:18px;">
                    Additional payments already exist.
                    Use Stay Management to record further payments.
                </p>
            @endif

            <div class="detail-list" style="margin-top:18px;">
                <div class="detail-item">
                    <h4>Original Payment Method</h4>
                    <p>
                        {{ $booking->payment_method ? ucfirst($booking->payment_method) : 'N/A' }}
                    </p>
                </div>

                <div class="detail-item">
                    <h4>Original Recorded Payment</h4>
                    <p>৳ {{ number_format($recordedMinor / 100, 2) }}</p>
                </div>

                <div class="detail-item">
                    <h4>Original Verified Payment</h4>
                    <p>৳ {{ number_format($originalVerifiedMinor / 100, 2) }}</p>
                </div>

                <div class="detail-item">
                    <h4>Additional Verified Payments</h4>
                    <p>৳ {{ number_format($additionalVerifiedMinor / 100, 2) }}</p>
                </div>

                <div class="detail-item">
                    <h4>Total Verified Payments</h4>
                    <p>৳ {{ number_format($verifiedMinor / 100, 2) }}</p>
                </div>

                <div class="detail-item">
                    <h4>Remaining Balance</h4>
                    <p>
                        @if($canCalculate)
                            ৳ {{ number_format($dueMinor / 100, 2) }}
                        @else
                            Requires staff review
                        @endif
                    </p>
                </div>

                @if($canCalculate && $creditMinor > 0)
                    <div class="detail-item">
                        <h4>Overpayment</h4>
                        <p>
                            ৳ {{ number_format($creditMinor / 100, 2) }}
                            — staff review required
                        </p>
                    </div>
                @endif

                <div class="detail-item">
                    <h4>Original Transaction ID</h4>
                    <p>{{ $booking->transaction_id ?: 'N/A' }}</p>
                </div>

                <div class="detail-item">
                    <h4>Original Payment Status</h4>
                    <p>
                        <span class="status status-{{ $paymentStatus }}">
                            {{ ucfirst(str_replace('_', ' ', $paymentStatus)) }}
                        </span>
                    </p>
                </div>

                <div class="detail-item" style="grid-column:1 / -1;">
                    <h4>Payment Description</h4>
                    <p>{{ $booking->payment_description ?: 'N/A' }}</p>
                </div>

                <div class="detail-item" style="grid-column:1 / -1;">
                    <h4>Receipt File</h4>
                    <p>
                        @if($booking->payment_receipt)
                            <a
                                href="{{ asset('storage/' . $booking->payment_receipt) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                Open uploaded receipt
                            </a>
                        @else
                            No receipt uploaded
                        @endif
                    </p>
                </div>
            </div>

            @if($paymentStatus === 'submitted' && $booking->status !== 'cancelled' && $canManage && !$hasStayPayments)
                <div class="actions" style="margin-top:16px;">
                    <form
                        method="POST"
                        action="{{ route('bookings.verifyPayment', $booking) }}"
                    >
                        @csrf

                        <button type="submit" class="btn btn-success">
                            Verify Payment
                        </button>
                    </form>

                    <form
                        method="POST"
                        action="{{ route('bookings.rejectPayment', $booking) }}"
                    >
                        @csrf

                        <button type="submit" class="btn btn-danger">
                            Reject Payment
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div class="card" style="margin-top:24px; padding:20px;">
            <span class="page-title-badge">ADDITIONAL PAYMENTS</span>

            <h3>Stay Payment History</h3>

            <p class="muted">
                Extension payments cover nights already included in the
                current scheduled stay amount. Only verified payments
                reduce the balance.
            </p>

            <div style="overflow-x:auto;">
                <table style="width:100%;">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Details</th>
                            <th>Amount</th>
                            <th>Method / Reference</th>
                            <th>Verification</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($stayPayments as $payment)
                            <tr>
                                <td>
                                    {{ ucfirst(str_replace('_', ' ', $payment->type)) }}
                                </td>

                                <td>
                                    @if($payment->type === 'extension')
                                        {{ $payment->extra_nights }} extra night(s)

                                        @if($payment->previous_check_out && $payment->new_check_out)
                                            <br>

                                            {{ \Illuminate\Support\Carbon::parse($payment->previous_check_out)->format('d M Y') }}
                                            →
                                            {{ \Illuminate\Support\Carbon::parse($payment->new_check_out)->format('d M Y') }}
                                        @endif
                                    @elseif($payment->type === 'overstay')
                                        Balance settlement including late stay charges
                                    @else
                                        Outstanding balance settlement
                                    @endif
                                </td>

                                <td>
                                    ৳ {{ number_format((float) $payment->amount, 2) }}
                                </td>

                                <td>
                                    {{ ucfirst($payment->payment_method) }}
                                    <br>
                                    {{ $payment->transaction_id ?: 'No transaction reference' }}
                                </td>

                                <td>
                                    @if($payment->verified_at)
                                        Verified
                                        <br>

                                        {{ \Illuminate\Support\Carbon::parse($payment->verified_at)->setTimezone($timezone)->format('d M Y, H:i T') }}
                                    @else
                                        Not verified — excluded from paid total
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    No additional stay payments recorded.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($booking->status === 'checked_in' && (!$canCalculate || $dueMinor > 0))
            <div class="alert alert-warning" style="margin-top:20px;">
                Financial checkout requires a valid bill and no outstanding
                balance. Open Stay Management to resolve the bill.
                If the guest has physically left, record their actual
                departure there so late charges stop accruing.
            </div>
        @endif

        <div class="actions" style="margin-top:22px;">
            @if($booking->status === 'pending' && $paymentStatus === 'verified' && $canManage)
                <form
                    method="POST"
                    action="{{ route('bookings.confirm', $booking) }}"
                >
                    @csrf

                    <button type="submit" class="btn btn-success">
                        Confirm
                    </button>
                </form>
            @endif

            @if($booking->status === 'confirmed' && $canHandleStay)
                <form
                    method="POST"
                    action="{{ route('bookings.checkin', $booking) }}"
                >
                    @csrf

                    <button type="submit" class="btn btn-primary">
                        Check In
                    </button>
                </form>
            @endif

            @if($booking->status === 'checked_in' && $canHandleStay)
                <form
                    method="POST"
                    action="{{ route('bookings.checkout', $booking) }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="btn btn-dark"
                        @disabled(!$canCalculate || $dueMinor > 0)
                    >
                        Complete Checkout
                    </button>
                </form>
            @endif

            @if(in_array($booking->status, ['pending', 'confirmed'], true) && $canManage)
                <form
                    method="POST"
                    action="{{ route('bookings.cancel', $booking) }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="btn btn-danger"
                        onclick="return confirm('Cancel this booking?')"
                    >
                        Cancel
                    </button>
                </form>
            @endif

            @if($isAdmin && !empty($booking->guest->email))
                <form
                    method="POST"
                    action="{{ route('bookings.sendReceipt', $booking) }}"
                >
                    @csrf

                    <button type="submit" class="btn btn-secondary">
                        Send Receipt
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>