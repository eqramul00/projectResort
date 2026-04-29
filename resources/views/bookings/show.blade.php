<x-app-layout>
    @php
        $checkIn = \Carbon\Carbon::parse($booking->check_in);
        $checkOut = \Carbon\Carbon::parse($booking->check_out);
        $nights = $checkIn->diffInDays($checkOut);
        $roomPrice = $booking->room->price ?? 0;
        $totalAmount = $nights * $roomPrice;
    @endphp

    <div class="card">
        <div class="card-header">
            <div>
                <span class="page-title-badge">BOOKING DETAILS</span>
                <h2>Booking #{{ $booking->id }}</h2>
                <p class="muted">Full reservation information, payment details, and stay status.</p>
            </div>
            <div class="actions">
                <a href="{{ route('bookings.edit', $booking) }}" class="btn btn-warning">Edit</a>
                <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Back</a>
                <a href="{{ route('bookings.invoice', $booking) }}" class="btn btn-dark">Download Invoice</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom: 16px;">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error" style="margin-bottom: 16px;">{{ session('error') }}</div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning" style="margin-bottom: 16px;">{{ session('warning') }}</div>
        @endif

        <div class="detail-list">
            <div class="detail-item">
                <h4>Room</h4>
                <p>{{ $booking->room->number }} - {{ $booking->room->name }}</p>
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
                <h4>Email</h4>
                <p>{{ $booking->guest->email ?: 'N/A' }}</p>
            </div>

            <div class="detail-item">
                <h4>Status</h4>
                <p>
                    <span class="status status-{{ $booking->status }}">
                        {{ str_replace('_', ' ', $booking->status) }}
                    </span>
                </p>
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
                <p>৳ {{ number_format($roomPrice, 2) }}</p>
            </div>

            <div class="detail-item">
                <h4>Total Nights</h4>
                <p>{{ $nights }}</p>
            </div>

            <div class="detail-item">
                <h4>Total Amount</h4>
                <p>৳ {{ number_format($totalAmount, 2) }}</p>
            </div>
        </div>

        <div class="card" style="margin-top: 24px; padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;">
                <div>
                    <span class="page-title-badge">PAYMENT INFO</span>
                    <h3 style="margin: 8px 0 4px;">Payment Details</h3>
                    <p class="muted" style="margin: 0;">Guest-submitted or manually added payment information.</p>
                </div>

                <div class="actions">
                    @if(auth()->user()->hasAnyRole(['admin','manager']))
                        <a href="{{ route('bookings.manual.payment', $booking) }}" class="btn btn-primary">
                            Add Payment
                        </a>
                    @endif

                    @if($booking->payment_receipt)
                        <a href="{{ asset('storage/' . $booking->payment_receipt) }}" target="_blank" class="btn btn-secondary">
                            View Receipt
                        </a>
                    @endif
                </div>
            </div>

            <div class="detail-list" style="margin-top: 18px;">
                <div class="detail-item">
                    <h4>Payment Method</h4>
                    <p>{{ $booking->payment_method ? ucfirst($booking->payment_method) : 'N/A' }}</p>
                </div>

                <div class="detail-item">
                    <h4>Paid Amount</h4>
                    <p>৳ {{ number_format($booking->paid_amount ?? 0, 2) }}</p>
                </div>

                <div class="detail-item">
                    <h4>Transaction ID</h4>
                    <p>{{ $booking->transaction_id ?: 'N/A' }}</p>
                </div>

                <div class="detail-item">
                    <h4>Payment Status</h4>
                    <p>
                        <span class="status status-{{ $booking->payment_status ?? 'pending' }}">
                            {{ str_replace('_', ' ', $booking->payment_status ?? 'pending') }}
                        </span>
                    </p>
                </div>

                <div class="detail-item" style="grid-column: 1 / -1;">
                    <h4>Payment Description</h4>
                    <p>{{ $booking->payment_description ?: 'N/A' }}</p>
                </div>

                <div class="detail-item" style="grid-column: 1 / -1;">
                    <h4>Receipt File</h4>
                    <p>
                        @if($booking->payment_receipt)
                            <a href="{{ asset('storage/' . $booking->payment_receipt) }}" target="_blank">
                                Open uploaded receipt
                            </a>
                        @else
                            No receipt uploaded
                        @endif
                    </p>
                </div>
            </div>

            @if($booking->payment_status === 'submitted' && auth()->user()->canManageBookings())
                <div class="actions" style="margin-top: 16px;">
                    <form method="POST" action="{{ route('bookings.verifyPayment', $booking) }}">
                        @csrf
                        <button type="submit" class="btn btn-success">Verify Payment</button>
                    </form>

                    <form method="POST" action="{{ route('bookings.rejectPayment', $booking) }}">
                        @csrf
                        <button type="submit" class="btn btn-danger">Reject Payment</button>
                    </form>
                </div>
            @endif
        </div>

        <div style="margin-top: 22px;" class="actions">
            @if($booking->status === 'pending' && auth()->user()->canManageBookings())
                <form method="POST" action="{{ route('bookings.confirm', $booking) }}">
                    @csrf
                    <button type="submit" class="btn btn-success">Confirm</button>
                </form>
            @endif

            @if($booking->status === 'confirmed' && auth()->user()->hasAnyRole(['admin','manager','reception']))
                <form method="POST" action="{{ route('bookings.checkin', $booking) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">Check In</button>
                </form>
            @endif

            @if($booking->status === 'checked_in' && auth()->user()->hasAnyRole(['admin','manager','reception']))
                <form method="POST" action="{{ route('bookings.checkout', $booking) }}">
                    @csrf
                    <button type="submit" class="btn btn-dark">Check Out</button>
                </form>
            @endif

            @if(in_array($booking->status, ['pending', 'confirmed', 'checked_in']) && auth()->user()->canManageBookings())
                <form method="POST" action="{{ route('bookings.cancel', $booking) }}">
                    @csrf
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Cancel this booking?')">Cancel</button>
                </form>
            @endif

            @if($booking->guest->email)
                <form method="POST" action="{{ route('bookings.sendReceipt', $booking) }}">
                    @csrf
                    <button type="submit" class="btn btn-secondary">Send Receipt</button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>