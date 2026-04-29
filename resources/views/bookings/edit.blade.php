<x-app-layout>
    <div class="card">
        <div class="card-header">
            <div>
                <span class="page-title-badge">UPDATE RESERVATION</span>
                <h2>Edit Booking</h2>
                <p class="muted">Update room, guest, dates, or booking status.</p>
            </div>
            <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Back</a>
        </div>

        <form method="POST" action="{{ route('bookings.update', $booking) }}">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label>Room</label>
                    <select name="room_id">
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ old('room_id', $booking->room_id) == $room->id ? 'selected' : '' }}>
                                {{ $room->number }} - {{ $room->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('room_id') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Guest</label>
                    <select name="guest_id">
                        @foreach($guests as $guest)
                            <option value="{{ $guest->id }}" {{ old('guest_id', $booking->guest_id) == $guest->id ? 'selected' : '' }}>
                                {{ $guest->full_name }} - {{ $guest->phone }}
                            </option>
                        @endforeach
                    </select>
                    @error('guest_id') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Check In</label>
                    <input type="date" name="check_in" value="{{ old('check_in', $booking->check_in) }}">
                    @error('check_in') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Check Out</label>
                    <input type="date" name="check_out" value="{{ old('check_out', $booking->check_out) }}">
                    @error('check_out') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group full">
                    <label>Status</label>
                    <select name="status">
                        <option value="pending" {{ old('status', $booking->status) === 'pending' ? 'selected' : '' }}>pending</option>
                        <option value="confirmed" {{ old('status', $booking->status) === 'confirmed' ? 'selected' : '' }}>confirmed</option>
                        <option value="checked_in" {{ old('status', $booking->status) === 'checked_in' ? 'selected' : '' }}>checked_in</option>
                        <option value="checked_out" {{ old('status', $booking->status) === 'checked_out' ? 'selected' : '' }}>checked_out</option>
                        <option value="cancelled" {{ old('status', $booking->status) === 'cancelled' ? 'selected' : '' }}>cancelled</option>
                    </select>
                    @error('status') <div class="field-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Update Booking</button>
            </div>
        </form>
    </div>
</x-app-layout>