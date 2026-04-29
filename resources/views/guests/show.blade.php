<x-app-layout>
    <div class="card">
        <div class="card-header">
            <div>
                <span class="page-title-badge">GUEST DETAILS</span>
                <h2>{{ $guest->full_name }}</h2>
                <p class="muted">Profile and booking history for this guest.</p>
            </div>
            <div class="actions">
                <a href="{{ route('guests.edit', $guest) }}" class="btn btn-warning">Edit</a>
                <a href="{{ route('guests.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <div class="detail-list">
            <div class="detail-item">
                <h4>Full Name</h4>
                <p>{{ $guest->full_name }}</p>
            </div>

            <div class="detail-item">
                <h4>Phone</h4>
                <p>{{ $guest->phone }}</p>
            </div>

            <div class="detail-item">
                <h4>Email</h4>
                <p>{{ $guest->email ?: '-' }}</p>
            </div>

            <div class="detail-item">
                <h4>Address</h4>
                <p>{{ $guest->address ?: '-' }}</p>
            </div>
        </div>

        <div style="margin-top: 28px;">
            <h3 style="margin-bottom: 14px;">Booking History</h3>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Room</th>
                        <th>Dates</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guest->bookings->sortByDesc('created_at') as $booking)
                        <tr>
                            <td>#{{ $booking->id }}</td>
                            <td>
                                @if($booking->room)
                                    {{ $booking->room->number }} - {{ $booking->room->name }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $booking->check_in }} → {{ $booking->check_out }}</td>
                            <td>
                                <span class="status status-{{ $booking->status }}">
                                    {{ str_replace('_', ' ', $booking->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="muted">No booking history found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>