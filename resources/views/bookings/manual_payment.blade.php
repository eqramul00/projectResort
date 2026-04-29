<x-app-layout>
    @php
        $checkIn = \Carbon\Carbon::parse($booking->check_in);
        $checkOut = \Carbon\Carbon::parse($booking->check_out);
        $nights = $checkIn->diffInDays($checkOut);
        $roomPrice = $booking->room->price ?? 0;
        $totalAmount = $nights * $roomPrice;
    @endphp

    <div class="card" style="max-width: 850px; margin: 40px auto;">
        <div class="card-header">
            <div>
                <span class="page-title-badge">MANUAL PAYMENT</span>
                <h2>Add Payment for Booking #{{ $booking->id }}</h2>
                <p class="muted">Admin or manager can add payment manually for this booking.</p>
            </div>
            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-secondary">Back</a>
        </div>

        @if($errors->any())
            <div class="alert alert-error" style="margin-bottom: 16px;">
                <ul style="margin:0; padding-left:18px;">
                    @foreach($errors->all() as $error)
                        <li style="margin-bottom:6px;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="detail-list" style="margin-bottom: 24px;">
            <div class="detail-item">
                <h4>Guest</h4>
                <p>{{ $booking->guest->full_name }}</p>
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
                <h4>Total Nights</h4>
                <p>{{ $nights }}</p>
            </div>

            <div class="detail-item">
                <h4>Total Amount</h4>
                <p>৳ {{ number_format($totalAmount, 2) }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('bookings.manual.payment.submit', $booking) }}">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label>Payment Method</label>
                    <select name="payment_method">
                        <option value="">Select Method</option>
                        <option value="bkash" {{ old('payment_method', $booking->payment_method) == 'bkash' ? 'selected' : '' }}>bKash</option>
                        <option value="nagad" {{ old('payment_method', $booking->payment_method) == 'nagad' ? 'selected' : '' }}>Nagad</option>
                        <option value="rocket" {{ old('payment_method', $booking->payment_method) == 'rocket' ? 'selected' : '' }}>Rocket</option>
                        <option value="cash" {{ old('payment_method', $booking->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="bank" {{ old('payment_method', $booking->payment_method) == 'bank' ? 'selected' : '' }}>Bank</option>
                    </select>
                    @error('payment_method') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Paid Amount</label>
                    <input type="number" step="0.01" name="paid_amount" value="{{ old('paid_amount', $booking->paid_amount ?: $totalAmount) }}">
                    @error('paid_amount') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group full">
                    <label>Transaction ID</label>
                    <input type="text" name="transaction_id" value="{{ old('transaction_id', $booking->transaction_id) }}" placeholder="Enter transaction ID">
                    @error('transaction_id') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group full">
                    <label>Payment Description</label>
                    <textarea name="payment_description" rows="4" placeholder="Write payment details here">{{ old('payment_description', $booking->payment_description) }}</textarea>
                    @error('payment_description') <div class="field-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Save Payment</button>
            </div>
        </form>
    </div>
</x-app-layout>