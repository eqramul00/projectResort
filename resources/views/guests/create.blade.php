<x-app-layout>
    <div class="card">
        <div class="card-header">
            <div>
                <span class="page-title-badge">NEW GUEST</span>
                <h2>Create Guest</h2>
                <p class="muted">Add a guest profile to the system.</p>
            </div>
            <a href="{{ route('guests.index') }}" class="btn btn-secondary">Back</a>
        </div>

        <form method="POST" action="{{ route('guests.store') }}">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label>Full Name</label>
                    <input name="full_name" value="{{ old('full_name') }}" placeholder="Enter full name">
                    @error('full_name') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input name="phone" value="{{ old('phone') }}" placeholder="Enter phone number">
                    @error('phone') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input name="email" type="email" value="{{ old('email') }}" placeholder="Enter email address">
                    @error('email') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <input name="address" value="{{ old('address') }}" placeholder="Enter address">
                    @error('address') <div class="field-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Save Guest</button>
            </div>
        </form>
    </div>
</x-app-layout>