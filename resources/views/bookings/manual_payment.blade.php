<x-app-layout>
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

        $totalAmount = $canCalculate
            ? $totalMinor / 100
            : null;

        $recordedAmount = (float) ($booking->paid_amount ?? 0);

        $defaultAmount = $recordedAmount > 0
            ? number_format($recordedAmount, 2, '.', '')
            : ($canCalculate
                ? number_format($totalAmount, 2, '.', '')
                : '');

        $canSave = $booking->status !== 'cancelled';
    @endphp

    <div class="card" style="max-width:850px; margin:40px auto;">
        <div class="card-header">
            <div>
                <span class="page-title-badge">MANUAL PAYMENT</span>

                <h2>Add Payment for Booking #{{ $booking->id }}</h2>

                <p class="muted">
                    Admin or manager can record a verified payment
                    manually for this booking.
                </p>
            </div>

            <a href="{{ route('bookings.show', $booking) }}"
               class="btn btn-secondary">
                Back
            </a>
        </div>

        @if(session('error'))
            <div class="alert alert-error" style="margin-bottom:16px;">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error" style="margin-bottom:16px;">
                <ul style="margin:0; padding-left:18px;">
                    @foreach($errors->all() as $error)
                        <li style="margin-bottom:6px;">
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(!$hasAgreedRate)
            <div class="alert alert-warning" style="margin-bottom:16px;">
                The agreed rate has not been recorded for this booking.
                Check the original booking records before confirming
                the final bill. No current room price has been substituted.
            </div>
        @endif

        @if(!$validDates)
            <div class="alert alert-error" style="margin-bottom:16px;">
                The booking dates require correction.
                Check-out must be after check-in.
            </div>
        @endif

        @if($hasAgreedRate && $rateMinor < 0)
            <div class="alert alert-error" style="margin-bottom:16px;">
                The recorded agreed rate requires correction.
                The booking total cannot be calculated.
            </div>
        @endif

        <div class="detail-list" style="margin-bottom:24px;">
            <div class="detail-item">
                <h4>Guest</h4>
                <p>{{ $booking->guest->full_name ?? 'N/A' }}</p>
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
                <h4>Total Nights</h4>
                <p>{{ $nights ?? 'Requires correction' }}</p>
            </div>

            <div class="detail-item">
                <h4>Agreed Rate Per Night</h4>
                <p>
                    @if($hasAgreedRate && $rateMinor >= 0)
                        ৳ {{ number_format($rateMinor / 100, 2) }}
                    @else
                        Not recorded
                    @endif
                </p>
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
                <h4>Recorded Payment Amount</h4>
                <p>৳ {{ number_format($recordedAmount, 2) }}</p>
            </div>

            <div class="detail-item">
                <h4>Payment Status</h4>
                <p>
                    {{ ucfirst($booking->payment_status ?? 'pending') }}
                </p>
            </div>
        </div>

        @if($canSave)
            <div class="alert alert-warning" style="margin-bottom:20px;">
                Saving replaces the payment amount currently recorded
                for this booking and marks it as verified.
                Enter the total amount you have verified, not an
                additional instalment. Verify the payment before saving.
            </div>

            <form method="POST"
                  action="{{ route('bookings.manual.payment.submit', $booking) }}">
                @csrf

                <div class="form-grid">
                    <div class="form-group">
                        <label for="payment_method">Payment Method</label>

                        <select id="payment_method"
                                name="payment_method"
                                required>
                            <option value="">Select Method</option>

                            <option value="bkash" {{ old('payment_method', $booking->payment_method) === 'bkash' ? 'selected' : '' }}>
                                bKash
                            </option>

                            <option value="nagad" {{ old('payment_method', $booking->payment_method) === 'nagad' ? 'selected' : '' }}>
                                Nagad
                            </option>

                            <option value="rocket" {{ old('payment_method', $booking->payment_method) === 'rocket' ? 'selected' : '' }}>
                                Rocket
                            </option>

                            <option value="cash" {{ old('payment_method', $booking->payment_method) === 'cash' ? 'selected' : '' }}>
                                Cash
                            </option>

                            <option value="bank" {{ old('payment_method', $booking->payment_method) === 'bank' ? 'selected' : '' }}>
                                Bank
                            </option>
                        </select>

                        @error('payment_method')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="paid_amount">
                            Total Verified Payment Amount
                        </label>

                        <input id="paid_amount"
                               type="number"
                               step="0.01"
                               min="0.01"
                               max="99999999.99"
                               name="paid_amount"
                               value="{{ old('paid_amount', $defaultAmount) }}"
                               required>

                        @error('paid_amount')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group full">
                        <label for="transaction_id">Transaction ID</label>

                        <input id="transaction_id"
                               type="text"
                               name="transaction_id"
                               maxlength="100"
                               value="{{ old('transaction_id', $booking->transaction_id) }}"
                               placeholder="Enter transaction ID">

                        @error('transaction_id')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group full">
                        <label for="payment_description">
                            Payment Description
                        </label>

                        <textarea id="payment_description"
                                  name="payment_description"
                                  rows="4"
                                  maxlength="2000"
                                  placeholder="Write payment details here">{{ old('payment_description', $booking->payment_description) }}</textarea>

                        @error('payment_description')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div style="margin-top:20px;">
                    <button type="submit" class="btn btn-primary">
                        Save Verified Payment
                    </button>
                </div>
            </form>
        @else
            <div class="alert alert-error">
                Payments cannot be added to a cancelled booking.
            </div>
        @endif
    </div>
</x-app-layout>