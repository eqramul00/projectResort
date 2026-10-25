<x-guest-management-layout>
    <div class="card">
        <div class="card-header">
            <div>
                <span class="page-title-badge">NEW GUEST PROFILE</span>
                <h2>Add a guest</h2>
                <p class="muted">
                    Create a contact profile for staff-managed reservations.
                </p>
            </div>

            <a href="{{ route('guests.index') }}" class="btn btn-secondary">
                Back
            </a>
        </div>

        <div class="alert alert-info">
            This creates a contact profile only, not a login account.
            Guests create their own login through guest registration.
        </div>

        <form method="POST" action="{{ route('guests.store') }}">
            @csrf

            <div class="form-grid">
                <div class="form-group full">
                    <label for="fullName">Full name</label>
                    <input
                        id="fullName"
                        name="full_name"
                        maxlength="255"
                        autocomplete="name"
                        value="{{ old('full_name') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="phone">Phone number</label>
                    <input
                        id="phone"
                        type="tel"
                        name="phone"
                        maxlength="50"
                        autocomplete="tel"
                        value="{{ old('phone') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="email">Contact email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        maxlength="255"
                        autocomplete="email"
                        value="{{ old('email') }}"
                    >
                </div>

                <div class="form-group full">
                    <label for="address">Address</label>
                    <textarea
                        id="address"
                        name="address"
                        rows="4"
                        maxlength="1000"
                        autocomplete="street-address"
                    >{{ old('address') }}</textarea>
                </div>
            </div>

            <div class="actions" style="margin-top:22px;">
                <button type="submit" class="btn btn-primary">
                    Create Guest Profile
                </button>

                <a href="{{ route('guests.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-guest-management-layout>