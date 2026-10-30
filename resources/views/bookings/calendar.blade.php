<x-app-layout>
    <div class="card">
        <div class="card-header">
            <div>
                <span class="page-title-badge">BOOKING CALENDAR</span>
                <h2>Booking Calendar</h2>
                <p class="muted">
                    Stays overlapping the selected month,
                    grouped by check-in date.
                </p>
            </div>

            <a href="{{ route('bookings.index') }}"
               class="btn btn-secondary">
                Back to Bookings
            </a>
        </div>

        @if($errors->any())
            <div class="alert alert-error" role="alert">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="GET"
              action="{{ route('bookings.calendar') }}"
              style="display:flex; flex-wrap:wrap; align-items:flex-end; gap:12px; margin-bottom:20px;">
            <div style="flex:0 1 240px; min-width:0;">
                <label for="calendar-month"
                       style="display:block; margin-bottom:8px; font-weight:600;">
                    Select month
                </label>

                <input id="calendar-month"
                       type="month"
                       name="month"
                       value="{{ $selectedMonth }}"
                       required
                       style="display:block; box-sizing:border-box; width:100%; padding:12px 14px; border:1px solid #d1d5db; border-radius:10px; font:inherit;">
            </div>

            <button type="submit" class="btn btn-primary">
                Show Month
            </button>

            <a href="{{ route('bookings.calendar', ['month' => $currentMonth]) }}"
               class="btn btn-secondary">
                This Month
            </a>
        </form>

        <nav aria-label="Calendar month navigation"
             style="display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:12px; margin-bottom:12px;">
            <a href="{{ route('bookings.calendar', ['month' => $previousMonth]) }}"
               class="btn btn-secondary"
               rel="prev">
                ← Previous Month
            </a>

            <h3 style="margin:0;">{{ $monthLabel }}</h3>

            <a href="{{ route('bookings.calendar', ['month' => $nextMonth]) }}"
               class="btn btn-secondary"
               rel="next">
                Next Month →
            </a>
        </nav>

        <p class="muted" style="margin-bottom:24px;">
            {{ number_format($bookingCount) }} bookings overlap this month.
            Stays that began earlier appear under their original check-in date.
        </p>

        @forelse($bookings as $date => $items)
            <div style="margin-bottom:28px;">
                <h3 style="margin-bottom:12px;">
                    {{ $date }}
                </h3>

                <div style="overflow-x:auto;">
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

                                    <td>
                                        {{ $booking->room->number }}
                                        - {{ $booking->room->name }}
                                    </td>

                                    <td>
                                        {{ $booking->guest->full_name }}
                                    </td>

                                    <td>
                                        {{ $booking->check_out }}
                                    </td>

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
            </div>
        @empty
            <p class="muted">
                No bookings found for {{ $monthLabel }}.
            </p>
        @endforelse
    </div>
</x-app-layout>