<x-app-layout>
    <div class="card">
        <div class="card-header">
            <div>
                <span class="page-title-badge">GUEST DIRECTORY</span>
                <h2>Guests</h2>
                <p class="muted">Search guests by phone, name, or email and see their booking activity.</p>
            </div>
            <a href="{{ route('guests.create') }}" class="btn btn-primary">+ Add Guest</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('guests.index') }}" style="margin-bottom: 22px;">
            <div style="display:flex; gap:12px; flex-wrap:wrap; align-items:center;">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by phone, name, or email..."
                    style="flex:1; min-width:280px; padding:14px 16px; border-radius:14px; border:1px solid #dbe3ef;"
                >

                <button type="submit" class="btn btn-primary">Search</button>

                @if(request('search'))
                    <a href="{{ route('guests.index') }}" class="btn btn-secondary">Reset</a>
                @endif
            </div>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Guest</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Total Bookings</th>
                    <th>Latest Booking</th>
                    <th>Current Status</th>
                    <th style="width:240px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($guests as $guest)
                    @php
                        $latestBooking = $guest->bookings->sortByDesc('created_at')->first();
                        $activeBooking = $guest->bookings->firstWhere('status', 'checked_in');
                        $statusBooking = $activeBooking ?: $latestBooking;
                    @endphp

                    <tr>
                        <td>#{{ $guest->id }}</td>

                        <td>
                            <strong>{{ $guest->full_name }}</strong>
                            @if($guest->address)
                                <br><span class="muted">{{ $guest->address }}</span>
                            @endif
                        </td>

                        <td>{{ $guest->phone }}</td>

                        <td>{{ $guest->email ?: '-' }}</td>

                        <td>
                            <span class="chip">{{ $guest->bookings_count }}</span>
                        </td>

                        <td>
                            @if($latestBooking)
                                <strong>Room {{ $latestBooking->room->number ?? '-' }}</strong><br>
                                <span class="muted">{{ $latestBooking->check_in }} → {{ $latestBooking->check_out }}</span>
                            @else
                                <span class="muted">No bookings</span>
                            @endif
                        </td>

                        <td>
                            @if($statusBooking)
                                <span class="status status-{{ $statusBooking->status }}">
                                    {{ str_replace('_', ' ', $statusBooking->status) }}
                                </span>
                            @else
                                <span class="muted">No status</span>
                            @endif
                        </td>

                        <td>
                            <div class="actions">
                                <a href="{{ route('guests.show', $guest) }}" class="btn btn-secondary">View</a>
                                <a href="{{ route('guests.edit', $guest) }}" class="btn btn-warning">Edit</a>

                                <form method="POST" action="{{ route('guests.destroy', $guest) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="btn btn-danger"
                                        onclick="return confirm('Delete this guest?')"
                                    >
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="muted">No guests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>