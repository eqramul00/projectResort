<x-guest-management-layout>
    <div class="card">
        <div class="card-header">
            <div>
                <span class="page-title-badge">EDIT GUEST PROFILE</span>
                <h2>{{ $guest->full_name }}</h2>
                <p class="muted">Update this guest’s contact details.</p>
            </div>

            <a href="{{ route('guests.show', $guest) }}" class="btn btn-secondary">
                Back to Profile
            </a>
        </div>

        @if($guest->user_id !== null)
            <div class="alert alert-info">
                This profile is linked to a guest account.
                These changes update contact information only.
                They do not change the account’s login email,
                password or ownership.
            </div>
        @endif

        <form method="POST" action="{{ route('guests.update', $guest) }}">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group full">
                    <label for="fullName">Full name</label>
                    <input
                        id="fullName"
                        name="full_name"
                        maxlength="255"
                        autocomplete="name"
                        value="{{ old('full_name', $guest->full_name) }}"
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
                        value="{{ old('phone', $guest->phone) }}"
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
                        value="{{ old('email', $guest->email) }}"
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
                    >{{ old('address', $guest->address) }}</textarea>
                </div>
            </div>

            <div class="actions" style="margin-top:22px;">
                <button type="submit" class="btn btn-primary">
                    Save Contact Details
                </button>

                <a href="{{ route('guests.show', $guest) }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-guest-management-layout>