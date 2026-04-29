<x-app-layout>
    <div class="card">
        <div class="card-header">
            <div>
                <span class="page-title-badge">ADD NEW ROOM</span>
                <h2>Create Room</h2>
                <p class="muted">Enter room details and upload multiple pictures.</p>
            </div>
            <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Back</a>
        </div>

        <form method="POST" action="{{ route('rooms.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label>Room Number</label>
                    <input name="number" value="{{ old('number') }}">
                    @error('number') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Room Name</label>
                    <input name="name" value="{{ old('name') }}">
                    @error('name') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Type</label>
                    <input name="type" value="{{ old('type') }}">
                    @error('type') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Price</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price') }}">
                    @error('price') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group full">
                    <label>Status</label>
                    <select name="status">
                        <option value="available">available</option>
                        <option value="occupied">occupied</option>
                        <option value="maintenance">maintenance</option>
                    </select>
                    @error('status') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group full">
                    <label>Room Pictures</label>
                    <input type="file" name="images[]" multiple accept="image/*">
                    @error('images.*') <div class="field-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Save Room</button>
            </div>
        </form>
    </div>
</x-app-layout>