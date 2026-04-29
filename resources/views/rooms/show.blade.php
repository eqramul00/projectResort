<x-app-layout>
    <div class="card">
        <div class="card-header">
            <div>
                <span class="page-title-badge">ROOM DETAILS</span>
                <h2>{{ $room->name }}</h2>
                <p class="muted">Detailed information for this room.</p>
            </div>
            <div class="actions">
                <a href="{{ route('rooms.edit', $room) }}" class="btn btn-warning">Edit</a>
                <a href="{{ route('rooms.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <div class="detail-list">
            <div class="detail-item">
                <h4>Room Number</h4>
                <p>{{ $room->number }}</p>
            </div>

            <div class="detail-item">
                <h4>Room Name</h4>
                <p>{{ $room->name }}</p>
            </div>

            <div class="detail-item">
                <h4>Type</h4>
                <p>{{ $room->type }}</p>
            </div>

            <div class="detail-item">
                <h4>Price</h4>
                <p>৳ {{ number_format($room->price, 2) }}</p>
            </div>

            <div class="detail-item">
                <h4>Status</h4>
                <p><span class="status status-{{ $room->status }}">{{ $room->status }}</span></p>
            </div>
        </div>

        <div style="margin-top:24px;">
            <h3>Room Gallery</h3>

            @if($room->images->count())
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:18px;">
                    @foreach($room->images as $img)
                        <img src="{{ asset('storage/' . $img->image_path) }}" alt="Room Image" style="width:100%;height:220px;object-fit:cover;border-radius:16px;border:1px solid #e5e7eb;">
                    @endforeach
                </div>
            @else
                <p class="muted">No pictures uploaded yet.</p>
            @endif
        </div>
    </div>
</x-app-layout>