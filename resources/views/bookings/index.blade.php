<x-app-layout>
    <div class="card">
        <div class="card-header">
            <div>
                <span class="page-title-badge">RESERVATION CENTER</span>
                <h2>Bookings</h2>
                <p class="muted">
                    Manage booking requests, confirmations, check-ins,
                    check-outs, cancellations, and payments.
                </p>
            </div>

            <a href="{{ route('bookings.create') }}"
               class="btn btn-primary">
                + Create Booking
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error" role="alert">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="GET"
              action="{{ route('bookings.index') }}"
              style="display:flex; flex-wrap:wrap; align-items:flex-end; gap:12px; margin-bottom:20px;">
            @if(request('status'))
                <input type="hidden"
                       name="status"
                       value="{{ request('status') }}">
            @endif

            <div style="flex:1 1 260px; min-width:0;">
                <label for="booking-search"
                       style="display:block; margin-bottom:8px; font-weight:600;">
                    Search bookings
                </label>

                <input id="booking-search"
                       type="search"
                       name="search"
                       value="{{ old('search', $search) }}"
                       maxlength="100"
                       placeholder="Booking ID, guest, phone, email or room"
                       style="display:block; box-sizing:border-box; width:100%; padding:12px 14px; border:1px solid #d1d5db; border-radius:10px; font:inherit;">
            </div>

            <button type="submit" class="btn btn-primary">
                Search
            </button>

            @if($search !== '')
                <a href="{{ route('bookings.index', ['status' => request('status')]) }}"
                   class="btn btn-secondary">
                    Clear search
                </a>
            @endif
        </form>

        <div style="display:flex; flex-wrap:wrap; gap:12px; margin-bottom:20px;">
            <a href="{{ route('bookings.index', ['search' => $search]) }}"
               class="btn {{ request('status') ? 'btn-secondary' : 'btn-dark' }}">
                All
            </a>

            <a href="{{ route('bookings.calendar') }}"
               class="btn btn-secondary">
                Calendar View
            </a>

            <a href="{{ route('bookings.index', ['status' => 'pending', 'search' => $search]) }}"
               class="btn {{ request('status') === 'pending' ? 'btn-dark' : 'btn-secondary' }}">
                Pending
            </a>

            <a href="{{ route('bookings.index', ['status' => 'confirmed', 'search' => $search]) }}"
               class="btn {{ request('status') === 'confirmed' ? 'btn-dark' : 'btn-secondary' }}">
                Confirmed
            </a>

            <a href="{{ route('bookings.index', ['status' => 'checked_in', 'search' => $search]) }}"
               class="btn {{ request('status') === 'checked_in' ? 'btn-dark' : 'btn-secondary' }}">
                Checked In
            </a>

            <a href="{{ route('bookings.index', ['status' => 'checked_out', 'search' => $search]) }}"
               class="btn {{ request('status') === 'checked_out' ? 'btn-dark' : 'btn-secondary' }}">
                Checked Out
            </a>

            <a href="{{ route('bookings.index', ['status' => 'cancelled', 'search' => $search]) }}"
               class="btn {{ request('status') === 'cancelled' ? 'btn-dark' : 'btn-secondary' }}">
                Cancelled
            </a>
        </div>

        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Room</th>
                        <th>Guest</th>
                        <th>Stay Dates</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Paid</th>
                        <th>Receipt</th>
                        <th style="width:520px;">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($bookings as $b)
                        <tr>
                            <td>#{{ $b->id }}</td>

                            <td>
                                <strong>{{ $b->room->number }}</strong><br>
                                <span class="muted">
                                    {{ $b->room->name }}
                                </span>
                            </td>

                            <td>
                                <strong>{{ $b->guest->full_name }}</strong><br>
                                <span class="muted">
                                    {{ $b->guest->phone }}
                                </span>
                            </td>

                            <td>
                                {{ $b->check_in }} → {{ $b->check_out }}
                            </td>

                            <td>
                                <span class="status status-{{ $b->status }}">
                                    {{ str_replace('_', ' ', $b->status) }}
                                </span>
                            </td>

                            <td>
                                <div>
                                    <strong>
                                        {{ $b->payment_method ? ucfirst($b->payment_method) : 'N/A' }}
                                    </strong><br>

                                    <span class="muted">
                                        {{ str_replace('_', ' ', $b->payment_status ?? 'pending') }}
                                    </span>
                                </div>
                            </td>

                            <td>
                                ৳ {{ number_format($b->paid_amount ?? 0, 2) }}
                            </td>

                            <td>
                                @if($b->payment_receipt)
                                    <a href="{{ asset('storage/' . $b->payment_receipt) }}"
                                       target="_blank"
                                       class="btn btn-secondary">
                                        View
                                    </a>
                                @else
                                    <span class="muted">No File</span>
                                @endif
                            </td>

                            <td>
                                <div class="actions">
                                    <a href="{{ route('bookings.show', $b) }}"
                                       class="btn btn-secondary">
                                        View
                                    </a>

                                    <a href="{{ route('bookings.edit', $b) }}"
                                       class="btn btn-warning">
                                        Edit
                                    </a>

                                    @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                                        <a href="{{ route('bookings.manual.payment', $b) }}"
                                           class="btn btn-primary">
                                            {{ $b->payment_method || $b->payment_receipt ? 'Update Payment' : 'Add Payment' }}
                                        </a>
                                    @endif

                                    @if(
                                        $b->status === 'pending'
                                        && in_array($b->payment_status, ['submitted', 'verified'], true)
                                        && auth()->user()->canManageBookings()
                                    )
                                        <form method="POST"
                                              action="{{ route('bookings.confirm', $b) }}">
                                            @csrf

                                            <button type="submit"
                                                    class="btn btn-success">
                                                Confirm
                                            </button>
                                        </form>
                                    @endif

                                    @if(
                                        $b->payment_status === 'submitted'
                                        && auth()->user()->canManageBookings()
                                    )
                                        <form method="POST"
                                              action="{{ route('bookings.verifyPayment', $b) }}">
                                            @csrf

                                            <button type="submit"
                                                    class="btn btn-success">
                                                Verify Payment
                                            </button>
                                        </form>

                                        <form method="POST"
                                              action="{{ route('bookings.rejectPayment', $b) }}">
                                            @csrf

                                            <button type="submit"
                                                    class="btn btn-danger">
                                                Reject Payment
                                            </button>
                                        </form>
                                    @endif

                                    @if(
                                        $b->status === 'confirmed'
                                        && auth()->user()->hasAnyRole(['admin', 'manager', 'reception'])
                                    )
                                        <form method="POST"
                                              action="{{ route('bookings.checkin', $b) }}">
                                            @csrf

                                            <button type="submit"
                                                    class="btn btn-primary">
                                                Check In
                                            </button>
                                        </form>
                                    @endif

                                    @if(
                                        $b->status === 'checked_in'
                                        && auth()->user()->hasAnyRole(['admin', 'manager', 'reception'])
                                    )
                                        <form method="POST"
                                              action="{{ route('bookings.checkout', $b) }}">
                                            @csrf

                                            <button type="submit"
                                                    class="btn btn-dark">
                                                Check Out
                                            </button>
                                        </form>
                                    @endif

                                    @if(
                                        in_array($b->status, ['pending', 'confirmed', 'checked_in'])
                                        && auth()->user()->canManageBookings()
                                    )
                                        <form method="POST"
                                              action="{{ route('bookings.cancel', $b) }}">
                                            @csrf

                                            <button type="submit"
                                                    class="btn btn-danger"
                                                    onclick="return confirm('Cancel this booking?')">
                                                Cancel
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST"
                                          action="{{ route('bookings.destroy', $b) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-danger"
                                                onclick="return confirm('Delete this booking?')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="muted">
                                No bookings found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <nav aria-label="Bookings pagination"
             style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px; margin-top:20px;">
            <p class="muted" style="margin:0;">
                @if($bookings->total() > 0)
                    Showing {{ $bookings->firstItem() }}–{{ $bookings->lastItem() }}
                    of {{ number_format($bookings->total()) }} bookings
                    · Page {{ $bookings->currentPage() }} of {{ $bookings->lastPage() }}
                @else
                    0 bookings
                @endif
            </p>

            <div style="display:flex; flex-wrap:wrap; gap:8px;">
                @if(!$bookings->onFirstPage())
                    <a href="{{ $bookings->previousPageUrl() }}"
                       class="btn btn-secondary"
                       rel="prev">
                        Previous
                    </a>
                @endif

                @if($bookings->hasMorePages())
                    <a href="{{ $bookings->nextPageUrl() }}"
                       class="btn btn-primary"
                       rel="next">
                        Next
                    </a>
                @endif
            </div>
        </nav>
    </div>

    <div class="card">
        <div class="card-header">
            <div>
                <span class="page-title-badge">BOOKING TIMELINE</span>
                <h2>Upcoming Stay Calendar</h2>
                <p class="muted">
                    Active stays with a checkout date after today,
                    sorted by arrival date.
                </p>
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Room</th>
                        <th>Guest</th>
                        <th>Status</th>
                        <th>Payment</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($calendarBookings as $item)
                        <tr>
                            <td>{{ $item->check_in }}</td>
                            <td>{{ $item->check_out }}</td>

                            <td>
                                {{ $item->room->number }}
                                - {{ $item->room->name }}
                            </td>

                            <td>{{ $item->guest->full_name }}</td>

                            <td>
                                <span class="status status-{{ $item->status }}">
                                    {{ str_replace('_', ' ', $item->status) }}
                                </span>
                            </td>

                            <td>
                                <strong>
                                    {{ $item->payment_method ? ucfirst($item->payment_method) : 'N/A' }}
                                </strong><br>

                                <span class="muted">
                                    {{ str_replace('_', ' ', $item->payment_status ?? 'pending') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="muted">
                                No bookings in calendar view.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <nav aria-label="Upcoming stays pagination"
             style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px; margin-top:20px;">
            <p class="muted" style="margin:0;">
                @if($calendarBookings->total() > 0)
                    Showing {{ $calendarBookings->firstItem() }}–{{ $calendarBookings->lastItem() }}
                    of {{ number_format($calendarBookings->total()) }} bookings
                    · Page {{ $calendarBookings->currentPage() }} of {{ $calendarBookings->lastPage() }}
                @else
                    0 bookings
                @endif
            </p>

            <div style="display:flex; flex-wrap:wrap; gap:8px;">
                @if(!$calendarBookings->onFirstPage())
                    <a href="{{ $calendarBookings->previousPageUrl() }}"
                       class="btn btn-secondary"
                       rel="prev">
                        Previous
                    </a>
                @endif

                @if($calendarBookings->hasMorePages())
                    <a href="{{ $calendarBookings->nextPageUrl() }}"
                       class="btn btn-primary"
                       rel="next">
                        Next
                    </a>
                @endif
            </div>
        </nav>
    </div>
</x-app-layout>