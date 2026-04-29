<x-app-layout>
    <div class="card">
        <div class="card-header">
            <div>
                <span class="page-title-badge">UPDATE GUEST</span>
                <h2>Edit Guest</h2>
                <p class="muted">Update guest information and contact details.</p>
            </div>
            <a href="{{ route('guests.index') }}" class="btn btn-secondary">Back</a>
        </div>

        <form method="POST" action="{{ route('guests.update', $guest) }}">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label>Full Name</label>
                    <input name="full_name" value="{{ old('full_name', $guest->full_name) }}">
                    @error('full_name') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Phone</label>
                    <input name="phone" value="{{ old('phone', $guest->phone) }}">
                    @error('phone') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input name="email" type="email" value="{{ old('email', $guest->email) }}">
                    @error('email') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <input name="address" value="{{ old('address', $guest->address) }}">
                    @error('address') <div class="field-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Update Guest</button>
            </div>
        </form>
    </div>
</x-app-layout>