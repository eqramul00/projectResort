<x-app-layout>
    @php
        $selectedGuest = request()->route('guest');
    @endphp

    <style>
        .guest-workspace {
            display: grid;
            grid-template-columns: 205px minmax(0, 1fr);
            gap: 24px;
            align-items: start;
        }

        .guest-workspace-nav {
            position: sticky;
            top: 24px;
            padding: 20px 14px;
            border: 1px solid #dce4d9;
            border-radius: 17px;
            background: #ffffff;
        }

        .guest-workspace-nav .guest-nav-label {
            display: block;
            margin: 0 10px 8px;
            color: #73836e;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .guest-workspace-nav h2 {
            margin: 0 10px 20px;
            font-size: 17px;
            color: #233830;
        }

        .guest-workspace-links {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .guest-workspace-links a {
            display: flex;
            align-items: center;
            min-height: 44px;
            padding: 11px 12px;
            border-radius: 9px;
            color: #60705f;
            font-size: 12px;
            font-weight: 700;
        }

        .guest-workspace-links a:hover {
            background: #f1f5ed;
        }

        .guest-workspace-links a.active {
            background: #eaf1e3;
            color: #254e29;
        }

        .guest-workspace-note {
            margin: 20px 10px 0;
            padding-top: 16px;
            border-top: 1px solid #e1e7dc;
            color: #7a8774;
            font-size: 11px;
            line-height: 1.7;
        }

        .guest-workspace-content {
            min-width: 0;
        }

        .guest-toolbar {
            display: flex;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 22px;
        }

        .guest-search {
            flex: 1 1 220px;
            min-width: 0;
        }

        .guest-search label {
            display: block;
            margin-bottom: 7px;
            font-size: 12px;
        }

        .guest-toolbar input {
            width: 100%;
        }

        .guest-contact {
            display: block;
            margin-top: 5px;
            font-size: 12px;
            color: #6c7a6a;
            overflow-wrap: anywhere;
        }

        .guest-count {
            display: inline-block;
            padding: 5px 10px;
            background: #edf2e8;
            color: #426539;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 700;
        }

        .guest-pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-top: 23px;
            font-size: 12px;
        }

        .guest-pagination a {
            color: #173c32;
            font-weight: 700;
            padding: 10px 0;
        }

        .guest-description {
            color: #677663;
            font-size: 12px;
            line-height: 1.8;
        }

        .guest-workspace-content .detail-item p {
            font-size: 15px;
        }

        .guest-workspace-content .guest-address {
            white-space: pre-wrap;
        }

        @media (max-width: 1150px) {
            .guest-workspace {
                grid-template-columns: 1fr;
            }

            .guest-workspace-nav {
                position: static;
                padding: 15px;
            }

            .guest-workspace-nav h2 {
                margin-bottom: 12px;
            }

            .guest-workspace-links {
                flex-direction: row;
                overflow-x: auto;
            }

            .guest-workspace-links a {
                flex-shrink: 0;
            }

            .guest-workspace-note {
                display: none;
            }
        }

        @media (max-width: 560px) {
            .guest-workspace {
                gap: 16px;
            }

            .guest-toolbar .btn {
                flex: 1;
            }
        }
    </style>

    <div class="guest-workspace">
        <aside class="guest-workspace-nav" aria-label="Guest management">
            <span class="guest-nav-label">Staff workspace</span>
            <h2>Guest Management</h2>

            <nav class="guest-workspace-links">
                <a
                    href="{{ route('guests.index') }}"
                    class="{{ request()->routeIs('guests.index') ? 'active' : '' }}"
                    @if(request()->routeIs('guests.index')) aria-current="page" @endif
                >
                    Guest Directory
                </a>

                <a
                    href="{{ route('guests.create') }}"
                    class="{{ request()->routeIs('guests.create') ? 'active' : '' }}"
                    @if(request()->routeIs('guests.create')) aria-current="page" @endif
                >
                    Add Guest
                </a>

                @if($selectedGuest instanceof \App\Models\Guest)
                    <a
                        href="{{ route('guests.show', $selectedGuest) }}"
                        class="{{ request()->routeIs('guests.show') ? 'active' : '' }}"
                        @if(request()->routeIs('guests.show')) aria-current="page" @endif
                    >
                        Selected Profile
                    </a>

                    <a
                        href="{{ route('guests.edit', $selectedGuest) }}"
                        class="{{ request()->routeIs('guests.edit') ? 'active' : '' }}"
                        @if(request()->routeIs('guests.edit')) aria-current="page" @endif
                    >
                        Edit Contact Details
                    </a>
                @endif

                <a href="{{ route('bookings.index') }}">
                    All Bookings
                </a>
            </nav>

            <p class="guest-workspace-note">
                Manage guest profiles and open their reservations here.
                Guest login access remains separate from this staff workspace.
            </p>
        </aside>

        <div class="guest-workspace-content">
            @if(session('success'))
                <div class="alert alert-success" role="status">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error" role="alert">
                    <ul style="margin:0; padding-left:20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{ $slot }}
        </div>
    </div>
</x-app-layout>