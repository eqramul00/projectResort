<x-guest-management-layout>
    <div class="card">
        <div class="card-header">
            <div>
                <span class="page-title-badge">GUEST PROFILE #{{ $guest->id }}</span>
                <h2>{{ $guest->full_name }}</h2>
                <p class="muted">
                    Contact information, account link and booking history.
                </p>
            </div>

            <div class="actions">
                <a href="{{ route('guests.edit', $guest) }}" class="btn btn-warning">
                    Edit Details
                </a>

                <a href="{{ route('guests.index') }}" class="btn btn-secondary">
                    Directory
                </a>
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
                <h4>Contact Email</h4>
                <p>{{ $guest->email ?: 'Not recorded' }}</p>
            </div>

            <div class="detail-item">
                <h4>Total Bookings</h4>
                <p>{{ $guest->bookings_count }}</p>
            </div>

            <div class="detail-item">
                <h4>Address</h4>
                <p class="guest-address">{{ $guest->address ?: 'Not recorded' }}</p>
            </div>

            <div class="detail-item">
                <h4>Guest Login Account</h4>

                @if($guest->user)
                    <p>Linked account</p>
                    <span class="guest-contact">{{ $guest->user->email }}</span>
                @else
                    <p>No linked account</p>
                @endif
            </div>
        </div>

        <p class="guest-description" style="margin-top:18px;">
            Updating contact details does not change the linked login email,
            password or account ownership.
        </p>

        <div style="margin-top:28px;">
            <h3>Booking history</h3>

            <p class="guest-description">
                Open a booking to review payments, verify a submission,
                confirm a reservation or manage check-in and checkout.
                Available actions depend on your staff role.
            </p>

            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Booking</th>
                            <th>Room</th>
                            <th>Stay Dates</th>
                            <th>Booking Status</th>
                            <th>Payment Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>#{{ $booking->id }}</td>

                                <td>
                                    {{ $booking->room->number ?? 'N/A' }}
                                    <span class="guest-contact">
                                        {{ $booking->room->name ?? 'Room unavailable' }}
                                    </span>
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}
                                    <span class="guest-contact">
                                        to {{ \Carbon\Carbon::parse($booking->check_out)->format('d M Y') }}
                                    </span>
                                </td>

                                <td>
                                    <span class="status status-{{ $booking->status }}">
                                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                    </span>
                                </td>

                                <td>
                                    <span class="status status-{{ $booking->payment_status ?? 'pending' }}">
                                        {{ ucfirst($booking->payment_status ?? 'pending') }}
                                    </span>
                                </td>

                                <td>
                                    <a href="{{ route('bookings.show', $booking) }}"
                                       class="btn btn-secondary">
                                        Manage Booking
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="muted">
                                    No bookings have been recorded for this profile.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($bookings->hasPages())
                <nav class="guest-pagination" aria-label="Booking history pages">
                    <div>
                        @if(!$bookings->onFirstPage())
                            <a href="{{ $bookings->previousPageUrl() }}">← Previous</a>
                        @endif
                    </div>

                    <span>
                        Page {{ $bookings->currentPage() }}
                        of {{ $bookings->lastPage() }}
                    </span>

                    <div>
                        @if($bookings->hasMorePages())
                            <a href="{{ $bookings->nextPageUrl() }}">Next →</a>
                        @endif
                    </div>
                </nav>
            @endif
        </div>
    </div>
</x-guest-management-layout>