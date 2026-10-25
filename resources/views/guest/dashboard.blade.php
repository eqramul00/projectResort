<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings | McLovin Resort</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            padding: 24px 16px;
            min-height: 100vh;
            background: #0d0d0f;
            color: #ffffff;
            font-family: Arial, Helvetica, sans-serif;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        .header,
        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
        }

        .header {
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .btn {
            display: inline-block;
            padding: 12px 18px;
            border: 0;
            border-radius: 12px;
            background: #cfae47;
            color: #181818;
            font: inherit;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
        }

        .secondary {
            background: #30343e;
            color: #ffffff;
        }

        .card {
            padding: 24px;
            margin-bottom: 18px;
            border-radius: 20px;
            background: #17191f;
            border: 1px solid #34363e;
        }

        .details {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin: 20px 0;
        }

        dt {
            color: #f2de97;
            margin-bottom: 8px;
        }

        dd {
            margin: 0;
            color: #d7dbe2;
        }

        p { line-height: 1.7; }

        .success {
            padding: 16px;
            background: #123223;
            color: #bbf7d0;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .pagination {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: center;
            margin-top: 24px;
        }

        :focus-visible {
            outline: 3px solid #f2de97;
            outline-offset: 3px;
        }

        @media (max-width: 700px) {
            .details { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <main class="container">
        <header class="header">
            <div>
                <h1>My Bookings</h1>
                <p>Welcome, {{ $guest->full_name }}.</p>
            </div>

            <div class="actions">
                <a href="{{ route('welcome') }}" class="btn secondary">
                    Home
                </a>

                <a href="{{ route('public.book') }}" class="btn">
                    Make a Booking
                </a>

                <form method="POST" action="{{ route('guest.logout') }}">
                    @csrf
                    <button type="submit" class="btn secondary">
                        Log Out
                    </button>
                </form>
            </div>
        </header>

        @if(session('success'))
            <div class="success" role="status">
                {{ session('success') }}
            </div>
        @endif

        @forelse($bookings as $booking)
            <article class="card">
                <h2>Booking #{{ $booking->id }}</h2>

                <dl class="details">
                    <div>
                        <dt>Room</dt>
                        <dd>
                            {{ $booking->room->number ?? 'N/A' }} -
                            {{ $booking->room->name ?? 'N/A' }}
                        </dd>
                    </div>

                    <div>
                        <dt>Check In</dt>
                        <dd>
                            {{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}
                        </dd>
                    </div>

                    <div>
                        <dt>Check Out</dt>
                        <dd>
                            {{ \Carbon\Carbon::parse($booking->check_out)->format('d M Y') }}
                        </dd>
                    </div>

                    <div>
                        <dt>Booking Status</dt>
                        <dd>
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </dd>
                    </div>

                    <div>
                        <dt>Payment Status</dt>
                        <dd>
                            {{ ucfirst($booking->payment_status ?? 'pending') }}
                        </dd>
                    </div>

                    <div>
                        <dt>Agreed Nightly Rate</dt>
                        <dd>
                            @if($booking->agreed_rate !== null)
                                ৳ {{ number_format((float) $booking->agreed_rate, 2) }}
                            @else
                                Requires staff review
                            @endif
                        </dd>
                    </div>
                </dl>

                <a href="{{ route('public.book.payment', $booking) }}"
                   class="btn">
                    View Booking and Payment
                </a>
            </article>
        @empty
            <div class="card">
                <h2>No bookings yet</h2>
                <p>
                    Bookings you make while logged into this account
                    will appear here. Earlier bookings made without
                    an account are not automatically linked.
                </p>
            </div>
        @endforelse

        @if($bookings->hasPages())
            <nav class="pagination" aria-label="Booking pages">
                <div>
                    @if(!$bookings->onFirstPage())
                        <a href="{{ $bookings->previousPageUrl() }}"
                           class="btn secondary">
                            Previous
                        </a>
                    @endif
                </div>

                <span>
                    Page {{ $bookings->currentPage() }}
                    of {{ $bookings->lastPage() }}
                </span>

                <div>
                    @if($bookings->hasMorePages())
                        <a href="{{ $bookings->nextPageUrl() }}"
                           class="btn secondary">
                            Next
                        </a>
                    @endif
                </div>
            </nav>
        @endif
    </main>
</body>
</html>