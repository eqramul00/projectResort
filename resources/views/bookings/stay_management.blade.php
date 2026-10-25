<x-app-layout>
    <div class="card">
        <div class="card-header">
            <div>
                <span class="page-title-badge">STAY MANAGEMENT</span>
                <h2>Extensions and checkout</h2>
                <p class="muted">
                    Checkout deadline:
                    {{ config('hotel.checkout_time') }}
                    · {{ config('hotel.timezone') }}
                </p>
            </div>

            <a href="{{ route('bookings.index') }}" class="btn btn-secondary">
                Bookings
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-error" role="alert">
                <ul style="margin:0; padding-left:20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="GET" action="{{ route('stays.index') }}">
            <div class="form-grid">
                <div class="form-group">
                    <label for="stayBooking">Choose a checked-in stay</label>
                    <select id="stayBooking" name="booking" required>
                        <option value="">Choose booking</option>

                        @foreach($stays as $stay)
                            <option
                                value="{{ $stay->id }}"
                                {{ $booking && $booking->id === $stay->id ? 'selected' : '' }}
                            >
                                #{{ $stay->id }}
                                · {{ $stay->guest->full_name ?? 'Guest' }}
                                · Room {{ $stay->room->number ?? 'N/A' }}
                                {{ $stay->actual_departed_at ? '· Departure recorded' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="justify-content:flex-end;">
                    <button type="submit" class="btn btn-primary">Open Stay</button>
                </div>
            </div>
        </form>
    </div>

    @if($booking && $summary)
        <div class="card">
            <div class="card-header">
                <div>
                    <span class="page-title-badge">BOOKING #{{ $booking->id }}</span>
                    <h2>{{ $booking->guest->full_name ?? 'Guest' }}</h2>
                    <p class="muted">
                        Room {{ $booking->room->number ?? 'N/A' }}
                        · {{ $booking->status }}
                    </p>
                </div>

                <a href="{{ route('bookings.show', $booking) }}" class="btn btn-secondary">
                    Booking Details
                </a>
            </div>

            <div class="detail-list">
                <div class="detail-item">
                    <h4>Checkout deadline</h4>
                    <p>{{ $summary['deadline']->format('d M Y, H:i T') }}</p>
                </div>

                <div class="detail-item">
                    <h4>Actual departure</h4>
                    <p>
                        {{ $booking->actual_departed_at
                            ? $booking->actual_departed_at->copy()->timezone(config('hotel.timezone'))->format('d M Y, H:i T')
                            : 'Not recorded' }}
                    </p>
                </div>

                <div class="detail-item">
                    <h4>Scheduled room total</h4>
                    <p>
                        {{ $summary['priced'] ? 'BDT ' . number_format($summary['scheduled'] / 100, 2) : 'Requires review' }}
                    </p>
                </div>

                <div class="detail-item">
                    <h4>Overdue charge</h4>
                    <p>
                        {{ $summary['priced'] ? 'BDT ' . number_format($summary['late_charge'] / 100, 2) : 'Requires review' }}
                    </p>
                    <small>{{ $summary['late_nights'] }} additional day(s)</small>
                </div>

                <div class="detail-item">
                    <h4>Total verified payments</h4>
                    <p>BDT {{ number_format($summary['paid'] / 100, 2) }}</p>
                </div>

                <div class="detail-item">
                    <h4>Outstanding balance</h4>
                    <p>
                        {{ $summary['priced'] ? 'BDT ' . number_format($summary['balance'] / 100, 2) : 'Requires review' }}
                    </p>
                </div>
            </div>

            @if($summary['credit'] > 0)
                <div class="alert alert-warning" style="margin-top:18px;">
                    Overpayment of BDT {{ number_format($summary['credit'] / 100, 2) }}
                    requires staff review. No automatic refund is issued.
                </div>
            @endif

            @if(!$booking->actual_departed_at && $booking->status === 'checked_in')
                <p class="muted" style="margin-top:18px;">
                    Overdue charges are provisional until actual departure
                    is recorded. Each started additional hotel day after
                    the checkout deadline costs one agreed nightly rate.
                </p>
            @endif
        </div>

        @if($booking->status === 'checked_in')
            @if(auth()->user()->canManageBookings() && $summary['priced'])
                @if(!$booking->actual_departed_at)
                    <div class="card">
                        <h3>Collect payment and extend the stay</h3>

                        <p class="muted">
                            Verify availability before taking payment.
                            This submission checks availability again and
                            records payment and the extension together.
                            If it is rejected, do not let the guest occupy
                            the room for the extra dates.
                        </p>

                        <form
                            method="POST"
                            action="{{ route('stays.update', $booking) }}"
                            id="extensionForm"
                            data-rate="{{ $summary['rate'] }}"
                            data-checkout="{{ $booking->check_out }}"
                        >
                            @csrf
                            <input type="hidden" name="action" value="extension">
                            <input type="hidden" name="operation_key" value="{{ \Illuminate\Support\Str::uuid() }}">
                            <input type="hidden" name="expected_checkout" value="{{ $booking->check_out }}">

                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="newCheckout">New checkout date</label>
                                    <input
                                        id="newCheckout"
                                        type="date"
                                        name="new_checkout"
                                        min="{{ \Carbon\Carbon::parse($booking->check_out)->addDay()->format('Y-m-d') }}"
                                        required
                                    >
                                </div>

                                <div class="form-group">
                                    <label for="extensionAmount">Extra payment collected</label>
                                    <input
                                        id="extensionAmount"
                                        type="number"
                                        name="amount"
                                        min="0"
                                        max="99999999.99"
                                        step="0.01"
                                        required
                                    >
                                </div>

                                <div class="form-group">
                                    <label for="extensionMethod">Payment method</label>
                                    <select id="extensionMethod" name="payment_method" required>
                                        <option value="">Choose method</option>
                                        <option value="cash">Cash</option>
                                        <option value="bank">Bank</option>
                                        <option value="bkash">bKash</option>
                                        <option value="nagad">Nagad</option>
                                        <option value="rocket">Rocket</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="extensionReference">Transaction reference</label>
                                    <input id="extensionReference" name="transaction_id" maxlength="100">
                                </div>
                            </div>

                            <p id="extensionQuote" class="muted">
                                Select the new checkout date to see the required amount.
                            </p>

                            <label style="display:flex; gap:10px; align-items:flex-start;">
                                <input type="checkbox" name="payment_verified" value="1" required>
                                <span>I have received and verified this extra payment.</span>
                            </label>

                            <button type="submit" class="btn btn-primary" style="margin-top:18px;">
                                Record Payment & Extend
                            </button>
                        </form>
                    </div>
                @endif

                @if($summary['balance'] > 0)
                    <div class="card">
                        <h3>Settle the outstanding balance</h3>

                        <p class="muted">
                            This records payment only. It does not grant permission
                            to stay longer or change the reservation dates.
                        </p>

                        <form method="POST" action="{{ route('stays.update', $booking) }}">
                            @csrf

                            <input type="hidden" name="action" value="payment">
                            <input type="hidden" name="operation_key" value="{{ \Illuminate\Support\Str::uuid() }}">

                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="settlementAmount">Amount received</label>
                                    <input
                                        id="settlementAmount"
                                        type="number"
                                        name="amount"
                                        min="0.01"
                                        step="0.01"
                                        max="99999999.99"
                                        value="{{ number_format($summary['balance'] / 100, 2, '.', '') }}"
                                        required
                                    >
                                </div>

                                <div class="form-group">
                                    <label for="settlementMethod">Payment method</label>
                                    <select id="settlementMethod" name="payment_method" required>
                                        <option value="">Choose method</option>
                                        <option value="cash">Cash</option>
                                        <option value="bank">Bank</option>
                                        <option value="bkash">bKash</option>
                                        <option value="nagad">Nagad</option>
                                        <option value="rocket">Rocket</option>
                                    </select>
                                </div>

                                <div class="form-group full">
                                    <label for="settlementReference">Transaction reference</label>
                                    <input id="settlementReference" name="transaction_id" maxlength="100">
                                </div>
                            </div>

                            <label style="display:flex; gap:10px; align-items:flex-start; margin-top:18px;">
                                <input type="checkbox" name="payment_verified" value="1" required>
                                <span>I have received and verified this payment.</span>
                            </label>

                            <button type="submit" class="btn btn-primary" style="margin-top:18px;">
                                Record Verified Payment
                            </button>
                        </form>
                    </div>
                @endif
            @endif

            <div class="card">
                <h3>Departure and financial checkout</h3>

                @if(!$booking->actual_departed_at)
                    <p class="muted">
                        If the guest has physically left, record departure now.
                        This releases the room and fixes the departure time,
                        but does not mark an unpaid bill as settled.
                    </p>

                    <form method="POST" action="{{ route('stays.update', $booking) }}">
                        @csrf
                        <input type="hidden" name="action" value="departure">

                        <label style="display:flex; gap:10px; align-items:flex-start;">
                            <input type="checkbox" name="departure_confirmed" value="1" required>
                            <span>The guest has actually left the room.</span>
                        </label>

                        <button type="submit" class="btn btn-warning" style="margin-top:15px;">
                            Record Actual Departure
                        </button>
                    </form>
                @endif

                <form
                    method="POST"
                    action="{{ route('bookings.checkout', $booking) }}"
                    style="margin-top:22px;"
                >
                    @csrf

                    <button
                        type="submit"
                        class="btn btn-dark"
                        @if(!$summary['priced'] || $summary['balance'] > 0) disabled @endif
                    >
                        Complete Financial Checkout
                    </button>
                </form>
            </div>
        @endif

        <div class="card">
            <h3>Additional payment history</h3>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th>Recorded by</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>{{ ucfirst($payment->type) }}</td>
                                <td>BDT {{ number_format((float) $payment->amount, 2) }}</td>
                                <td>{{ ucfirst($payment->payment_method) }}</td>
                                <td>{{ $payment->transaction_id ?: '—' }}</td>
                                <td>{{ $payment->recorder->name ?? 'Former staff member' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No additional payments recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <script>
        (() => {
            const form = document.getElementById('extensionForm');

            if (!form) {
                return;
            }

            const dateInput = document.getElementById('newCheckout');
            const amountInput = document.getElementById('extensionAmount');
            const quote = document.getElementById('extensionQuote');

            function dayValue(value) {
                if (!/^\d{4}-\d{2}-\d{2}$/.test(value)) {
                    return null;
                }

                const parts = value.split('-').map(Number);
                return Date.UTC(parts[0], parts[1] - 1, parts[2]);
            }

            dateInput.addEventListener('input', () => {
                const original = dayValue(form.dataset.checkout);
                const updated = dayValue(dateInput.value);
                const rate = Number(form.dataset.rate);

                if (original === null || updated === null || updated <= original) {
                    quote.textContent = 'Choose a checkout date after the current one.';
                    return;
                }

                const nights = Math.round((updated - original) / 86400000);
                const amount = (rate * nights) / 100;

                amountInput.value = amount.toFixed(2);
                quote.textContent =
                    nights + ' extra night(s) · Required payment: BDT ' +
                    amount.toFixed(2);
            });
        })();
    </script>
</x-app-layout>