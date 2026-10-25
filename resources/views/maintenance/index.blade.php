@php
    /** @var \Illuminate\Pagination\LengthAwarePaginator<int, \App\Models\MaintenanceIssue> $issues */
    /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Room> $rooms */
    /** @var string $status */
    /** @var bool $canManage */
@endphp

<x-app-layout>
    <style>
        .maintenance-page {
            max-width: 1200px;
            margin: 0 auto;
        }

        .maintenance-page .maintenance-grid {
            display: grid;
            grid-template-columns: minmax(280px, .8fr) minmax(0, 1.4fr);
            gap: 24px;
            align-items: start;
            margin-top: 24px;
        }

        .maintenance-page .maintenance-panel {
            padding: 24px;
            border: 1px solid #dce3dc;
            border-radius: 18px;
            background: #ffffff;
            color: #233830;
        }

        .maintenance-page .maintenance-panel h3 {
            margin-top: 0;
        }

        .maintenance-page .maintenance-field {
            margin-bottom: 16px;
        }

        .maintenance-page label {
            display: block;
            margin-bottom: 7px;
            font-size: 13px;
            font-weight: 700;
        }

        .maintenance-page input:not([type="checkbox"]),
        .maintenance-page select,
        .maintenance-page textarea {
            width: 100%;
            min-height: 46px;
            padding: 11px 12px;
            border: 1px solid #cbd5ca;
            border-radius: 9px;
            background: #ffffff;
            color: #233830;
            font: inherit;
        }

        .maintenance-page textarea {
            min-height: 90px;
            resize: vertical;
        }

        .maintenance-page .maintenance-dates {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .maintenance-page .maintenance-check {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-weight: 400;
            line-height: 1.6;
        }

        .maintenance-page .maintenance-check input {
            margin-top: 4px;
        }

        .maintenance-page .maintenance-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            padding: 11px 17px;
            border: 0;
            border-radius: 9px;
            background: #173c32;
            color: #ffffff;
            font: inherit;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
        }

        .maintenance-page .maintenance-note {
            color: #627068;
            font-size: 12px;
            line-height: 1.7;
        }

        .maintenance-page .maintenance-alert {
            padding: 16px;
            margin-top: 18px;
            border-radius: 10px;
            line-height: 1.7;
        }

        .maintenance-page .maintenance-success {
            background: #eaf6e9;
            color: #315b2a;
        }

        .maintenance-page .maintenance-error {
            background: #fff0ec;
            color: #942e24;
        }

        .maintenance-page .maintenance-issue {
            padding-bottom: 22px;
            margin-bottom: 22px;
            border-bottom: 1px solid #dce3dc;
        }

        .maintenance-page .maintenance-issue:last-child {
            border-bottom: 0;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .maintenance-page .maintenance-issue h4 {
            margin: 10px 0;
            font-size: 18px;
        }

        .maintenance-page .maintenance-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            background: #eaf0e3;
            color: #34552b;
            font-size: 11px;
            font-weight: 700;
        }

        .maintenance-page .maintenance-description {
            white-space: pre-wrap;
            overflow-wrap: anywhere;
            font-size: 14px;
        }

        .maintenance-page details {
            margin-top: 15px;
        }

        .maintenance-page summary {
            cursor: pointer;
            color: #173c32;
            font-weight: 700;
            font-size: 13px;
            padding: 10px 0;
        }

        .maintenance-page .maintenance-pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-top: 24px;
            font-size: 13px;
        }

        @media (max-width: 850px) {
            .maintenance-page .maintenance-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .maintenance-page .maintenance-dates {
                grid-template-columns: 1fr;
            }

            .maintenance-page .maintenance-panel {
                padding: 20px;
            }
        }
    </style>

    <div class="maintenance-page">
        <div class="card">
            <div class="card-header">
                <div>
                    <span class="page-title-badge">ROOM MAINTENANCE</span>

                    <h2>Maintenance issues</h2>

                    <p class="muted">
                        Record repairs, track progress and manage dated room blocks.
                    </p>
                </div>

                <a href="{{ route('rooms.index') }}" class="btn btn-secondary">
                    View Rooms
                </a>
            </div>

            @if(session('success'))
                <div class="maintenance-alert maintenance-success" role="status">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="maintenance-alert maintenance-error" role="alert">
                    <ul style="margin:0; padding-left:20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="maintenance-grid">
            <section class="maintenance-panel">
                <h3>Report an issue</h3>

                <form method="POST" action="{{ route('maintenance.store') }}">
                    @csrf

                    <div class="maintenance-field">
                        <label for="newRoom">Room</label>

                        <select id="newRoom" name="room_id" required>
                            <option value="">Choose a room</option>

                            @foreach($rooms as $room)
                                <option
                                    value="{{ $room->id }}"
                                    {{ (string) old('room_id') === (string) $room->id ? 'selected' : '' }}
                                >
                                    {{ $room->number }} — {{ $room->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="maintenance-field">
                        <label for="newTitle">Issue title</label>

                        <input
                            id="newTitle"
                            name="title"
                            maxlength="150"
                            value="{{ old('title') }}"
                            placeholder="Example: Air conditioner repair"
                            required
                        >
                    </div>

                    <div class="maintenance-field">
                        <label for="newDescription">Description</label>

                        <textarea
                            id="newDescription"
                            name="description"
                            maxlength="3000"
                        >{{ old('description') }}</textarea>
                    </div>

                    <div class="maintenance-dates">
                        <div class="maintenance-field">
                            <label for="newStart">Start date</label>

                            <input
                                id="newStart"
                                type="date"
                                name="starts_on"
                                min="{{ now()->toDateString() }}"
                                value="{{ old('starts_on') }}"
                                required
                            >
                        </div>

                        <div class="maintenance-field">
                            <label for="newEnd">Available again on</label>

                            <input
                                id="newEnd"
                                type="date"
                                name="ends_on"
                                min="{{ now()->addDay()->toDateString() }}"
                                value="{{ old('ends_on') }}"
                                required
                            >
                        </div>
                    </div>

                    <input type="hidden" name="blocks_booking" value="0">

                    @if($canManage)
                        <div class="maintenance-field">
                            <label class="maintenance-check">
                                <input
                                    type="checkbox"
                                    name="blocks_booking"
                                    value="1"
                                    {{ old('blocks_booking') == '1' ? 'checked' : '' }}
                                >

                                <span>Block new bookings during these dates.</span>
                            </label>
                        </div>
                    @endif

                    <p class="maintenance-note">
                        The end date is exclusive. A block from the 10th
                        to the 12th prevents stays on the nights of the
                        10th and 11th.
                    </p>

                    <button type="submit" class="maintenance-button">
                        Record Issue
                    </button>
                </form>
            </section>

            <section class="maintenance-panel">
                <h3>Issue register</h3>

                <form
                    method="GET"
                    action="{{ route('maintenance.index') }}"
                    class="maintenance-field"
                >
                    <label for="statusFilter">Filter by status</label>

                    <select
                        id="statusFilter"
                        name="status"
                        onchange="this.form.submit()"
                    >
                        <option value="">All statuses</option>

                        <option value="open" {{ $status === 'open' ? 'selected' : '' }}>
                            Open
                        </option>

                        <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>
                            In progress
                        </option>

                        <option value="resolved" {{ $status === 'resolved' ? 'selected' : '' }}>
                            Resolved
                        </option>
                    </select>

                    <noscript>
                        <button type="submit" class="maintenance-button">
                            Apply Filter
                        </button>
                    </noscript>
                </form>

                @forelse($issues as $issue)
                    <article class="maintenance-issue">
                        <span class="maintenance-badge">
                            {{ ucfirst(str_replace('_', ' ', $issue->status)) }}
                        </span>

                        <h4>#{{ $issue->id }} — {{ $issue->title }}</h4>

                        <p class="maintenance-note">
                            Room {{ $issue->room->number ?? 'N/A' }}
                            · {{ $issue->starts_on->format('d M Y') }}
                            to {{ $issue->ends_on->format('d M Y') }}
                            · Reported by {{ $issue->creator->name ?? 'Former staff member' }}
                        </p>

                        <p class="maintenance-description">{{ $issue->description ?: 'No additional description.' }}</p>

                        <p class="maintenance-note">
                            @if($issue->status === 'resolved')
                                Resolved. This issue no longer blocks bookings.
                            @elseif($issue->blocks_booking)
                                Blocks bookings during the recorded date range.
                            @else
                                Issue only. Does not block bookings.
                            @endif
                        </p>

                        @if($canManage)
                            <details>
                                <summary>Update this issue</summary>

                                <form
                                    method="POST"
                                    action="{{ route('maintenance.update', $issue) }}"
                                >
                                    @csrf
                                    @method('PUT')

                                    <div class="maintenance-field">
                                        <label for="title-{{ $issue->id }}">
                                            Title
                                        </label>

                                        <input
                                            id="title-{{ $issue->id }}"
                                            name="title"
                                            maxlength="150"
                                            value="{{ $issue->title }}"
                                            required
                                        >
                                    </div>

                                    <div class="maintenance-field">
                                        <label for="description-{{ $issue->id }}">
                                            Description
                                        </label>

                                        <textarea
                                            id="description-{{ $issue->id }}"
                                            name="description"
                                            maxlength="3000"
                                        >{{ $issue->description }}</textarea>
                                    </div>

                                    <div class="maintenance-dates">
                                        <div class="maintenance-field">
                                            <label for="start-{{ $issue->id }}">
                                                Start date
                                            </label>

                                            <input
                                                id="start-{{ $issue->id }}"
                                                type="date"
                                                name="starts_on"
                                                value="{{ $issue->starts_on->format('Y-m-d') }}"
                                                required
                                            >
                                        </div>

                                        <div class="maintenance-field">
                                            <label for="end-{{ $issue->id }}">
                                                Available again on
                                            </label>

                                            <input
                                                id="end-{{ $issue->id }}"
                                                type="date"
                                                name="ends_on"
                                                value="{{ $issue->ends_on->format('Y-m-d') }}"
                                                required
                                            >
                                        </div>
                                    </div>

                                    <div class="maintenance-field">
                                        <label for="status-{{ $issue->id }}">
                                            Status
                                        </label>

                                        <select
                                            id="status-{{ $issue->id }}"
                                            name="status"
                                        >
                                            @if($issue->status !== 'resolved')
                                                <option
                                                    value="open"
                                                    {{ $issue->status === 'open' ? 'selected' : '' }}
                                                >
                                                    Open
                                                </option>

                                                <option
                                                    value="in_progress"
                                                    {{ $issue->status === 'in_progress' ? 'selected' : '' }}
                                                >
                                                    In progress
                                                </option>
                                            @endif

                                            <option
                                                value="resolved"
                                                {{ $issue->status === 'resolved' ? 'selected' : '' }}
                                            >
                                                Resolved
                                            </option>
                                        </select>
                                    </div>

                                    <input
                                        type="hidden"
                                        name="blocks_booking"
                                        value="0"
                                    >

                                    <label class="maintenance-check">
                                        <input
                                            type="checkbox"
                                            name="blocks_booking"
                                            value="1"
                                            {{ $issue->blocks_booking ? 'checked' : '' }}
                                        >

                                        <span>Block bookings while unresolved.</span>
                                    </label>

                                    <button
                                        type="submit"
                                        class="maintenance-button"
                                        style="margin-top:16px;"
                                    >
                                        Save Changes
                                    </button>
                                </form>
                            </details>
                        @endif
                    </article>
                @empty
                    <p class="maintenance-note">
                        No maintenance issues match this filter.
                    </p>
                @endforelse

                @if($issues->hasPages())
                    <nav
                        class="maintenance-pagination"
                        aria-label="Issue pages"
                    >
                        <div>
                            @if(!$issues->onFirstPage())
                                <a href="{{ $issues->previousPageUrl() }}">
                                    ← Previous
                                </a>
                            @endif
                        </div>

                        <span>
                            Page {{ $issues->currentPage() }}
                            of {{ $issues->lastPage() }}
                        </span>

                        <div>
                            @if($issues->hasMorePages())
                                <a href="{{ $issues->nextPageUrl() }}">
                                    Next →
                                </a>
                            @endif
                        </div>
                    </nav>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>