<x-app-layout>
    <div class="card">
        <div class="card-header">
            <div>
                <span class="page-title-badge">UPDATE ROOM</span>
                <h2>Edit Room</h2>
                <p class="muted">Update room details and manage pictures.</p>
            </div>
            <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Back</a>
        </div>

        <form method="POST" action="{{ route('rooms.update', $room) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label>Room Number</label>
                    <input name="number" value="{{ old('number', $room->number) }}">
                    @error('number') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Room Name</label>
                    <input name="name" value="{{ old('name', $room->name) }}">
                    @error('name') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Type</label>
                    <input name="type" value="{{ old('type', $room->type) }}">
                    @error('type') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label>Price</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $room->price) }}">
                    @error('price') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group full">
                    <label>Status</label>
                    <select name="status">
                        <option value="available" {{ $room->status === 'available' ? 'selected' : '' }}>available</option>
                        <option value="occupied" {{ $room->status === 'occupied' ? 'selected' : '' }}>occupied</option>
                        <option value="maintenance" {{ $room->status === 'maintenance' ? 'selected' : '' }}>maintenance</option>
                    </select>
                    @error('status') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group full">
                    <label>Add More Pictures</label>
                    <input type="file" name="images[]" multiple accept="image/*">
                    @error('images.*') <div class="field-error">{{ $message }}</div> @enderror
                </div>
            </div>

            @if($room->images->count())
                <div style="margin-top: 24px;">
                    <h3>Current Pictures</h3>
                    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;">
                        @foreach($room->images as $img)
                            <div style="border:1px solid #e5e7eb;padding:12px;border-radius:12px;">
                                <img src="{{ asset('storage/' . $img->image_path) }}" alt="Room Image" style="width:100%;height:160px;object-fit:cover;border-radius:10px;">
                                <label style="display:block;margin-top:10px;">
                                    <input type="checkbox" name="delete_images[]" value="{{ $img->id }}">
                                    Delete this image
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Update Room</button>
            </div>
        </form>
    </div>
</x-app-layout>