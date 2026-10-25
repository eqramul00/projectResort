<x-guest-management-layout>
    <div class="card">
        <div class="card-header">
            <div>
                <span class="page-title-badge">GUEST DIRECTORY</span>
                <h2>Guest profiles</h2>
                <p class="muted">
                    Search contact details and open each guest’s booking history.
                </p>
            </div>

            <a href="{{ route('guests.create') }}" class="btn btn-primary">
                + Add Guest
            </a>
        </div>

        <form method="GET"
              action="{{ route('guests.index') }}"
              class="guest-toolbar">
            <div class="guest-search">
                <label for="guestSearch">Search guests</label>

                <input
                    id="guestSearch"
                    type="search"
                    name="search"
                    maxlength="150"
                    value="{{ $search }}"
                    placeholder="Name, phone number or email"
                >
            </div>

            <button type="submit" class="btn btn-primary">Search</button>

            @if($search !== '')
                <a href="{{ route('guests.index') }}" class="btn btn-secondary">
                    Reset
                </a>
            @endif
        </form>

        <p class="guest-description">
            {{ $guests->total() }} matching guest profile(s).
            Profiles with the same contact details are not automatically merged.
        </p>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Guest</th>
                        <th>Contact</th>
                        <th>Bookings</th>
                        <th>Stay activity</th>
                        <th>Account</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($guests as $guest)
                        @php
                            $latestBooking = $guest->bookings->first();
                            $checkedInBooking = $guest->bookings
                                ->firstWhere('status', 'checked_in');

                            $statusBooking = $checkedInBooking ?: $latestBooking;
                        @endphp

                        <tr>
                            <td>
                                <strong>{{ $guest->full_name }}</strong>
                                <span class="guest-contact">Profile #{{ $guest->id }}</span>
                            </td>

                            <td>
                                {{ $guest->phone }}
                                <span class="guest-contact">
                                    {{ $guest->email ?: 'No email recorded' }}
                                </span>
                            </td>

                            <td>
                                <span class="guest-count">
                                    {{ $guest->bookings_count }}
                                </span>
                            </td>

                            <td>
                                @if($statusBooking)
                                    <span class="status status-{{ $statusBooking->status }}">
                                        {{ ucfirst(str_replace('_', ' ', $statusBooking->status)) }}
                                    </span>

                                    <span class="guest-contact">
                                        {{ $checkedInBooking ? 'Current stay' : 'Latest booking' }}
                                        #{{ $statusBooking->id }}
                                        · Room {{ $statusBooking->room->number ?? 'N/A' }}
                                    </span>
                                @else
                                    <span class="muted">No bookings</span>
                                @endif
                            </td>

                            <td>
                                {{ $guest->user_id !== null ? 'Linked login' : 'Contact profile' }}
                            </td>

                            <td>
                                <div class="actions">
                                    <a href="{{ route('guests.show', $guest) }}"
                                       class="btn btn-secondary">
                                        View
                                    </a>

                                    <a href="{{ route('guests.edit', $guest) }}"
                                       class="btn btn-warning">
                                        Edit
                                    </a>

                                    @if(auth()->user()->role === 'admin' && $guest->user_id === null && $guest->bookings_count === 0)
                                        <form method="POST"
                                              action="{{ route('guests.destroy', $guest) }}">
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-danger"
                                                onclick="return confirm('Permanently delete this guest profile?')"
                                            >
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="muted">
                                No guests match your search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($guests->hasPages())
            <nav class="guest-pagination" aria-label="Guest directory pages">
                <div>
                    @if(!$guests->onFirstPage())
                        <a href="{{ $guests->previousPageUrl() }}">← Previous</a>
                    @endif
                </div>

                <span>
                    Page {{ $guests->currentPage() }}
                    of {{ $guests->lastPage() }}
                </span>

                <div>
                    @if($guests->hasMorePages())
                        <a href="{{ $guests->nextPageUrl() }}">Next →</a>
                    @endif
                </div>
            </nav>
        @endif
    </div>
</x-guest-management-layout>