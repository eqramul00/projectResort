<x-app-layout>
    <div class="card">
        <div class="card-header">
            <div>
                <span class="page-title-badge">BOOKING CALENDAR</span>
                <h2>Booking Calendar</h2>
                <p class="muted">Bookings grouped by check-in date.</p>
            </div>
            <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Back to Bookings</a>
        </div>

        @forelse($bookings as $date => $items)
            <div style="margin-bottom: 28px;">
                <h3 style="margin-bottom: 12px;">{{ $date }}</h3>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Room</th>
                            <th>Guest</th>
                            <th>Check Out</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $booking)
                            <tr>
                                <td>#{{ $booking->id }}</td>
                                <td>{{ $booking->room->number }} - {{ $booking->room->name }}</td>
                                <td>{{ $booking->guest->full_name }}</td>
                                <td>{{ $booking->check_out }}</td>
                                <td>
                                    <span class="status status-{{ $booking->status }}">
                                        {{ str_replace('_', ' ', $booking->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <p class="muted">No bookings found.</p>
        @endforelse
    </div>
</x-app-layout>