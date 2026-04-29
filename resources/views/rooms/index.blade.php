<x-app-layout>
    <div class="card">
        <div class="card-header">
            <div>
                <span class="page-title-badge">ROOM MANAGEMENT</span>
                <h2>Rooms</h2>
                <p class="muted">Manage all resort rooms, prices, images, and availability.</p>
            </div>
            <a href="{{ route('rooms.create') }}" class="btn btn-primary">+ Add Room</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div style="display:flex; flex-wrap:wrap; gap:12px; margin-bottom:20px;">
            <a href="{{ route('rooms.index') }}"
               class="btn {{ request('status') ? 'btn-secondary' : 'btn-dark' }}">
                All
            </a>

            <a href="{{ route('rooms.index', ['status' => 'available']) }}"
               class="btn {{ request('status') === 'available' ? 'btn-dark' : 'btn-secondary' }}">
                Available
            </a>

            <a href="{{ route('rooms.index', ['status' => 'occupied']) }}"
               class="btn {{ request('status') === 'occupied' ? 'btn-dark' : 'btn-secondary' }}">
                Occupied
            </a>

            <a href="{{ route('rooms.index', ['status' => 'maintenance']) }}"
               class="btn {{ request('status') === 'maintenance' ? 'btn-dark' : 'btn-secondary' }}">
                Maintenance
            </a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Picture</th>
                    <th>Room Number</th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th style="width: 260px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rooms as $room)
                    <tr>
                        <td>#{{ $room->id }}</td>

                        <td>
                            @if($room->images->first())
                                <img
                                    src="{{ asset('storage/' . $room->images->first()->image_path) }}"
                                    alt="{{ $room->name }}"
                                    style="width:72px;height:72px;object-fit:cover;border-radius:14px;border:1px solid #e5e7eb;"
                                >
                            @else
                                <span class="muted">No image</span>
                            @endif
                        </td>

                        <td><strong>{{ $room->number }}</strong></td>
                        <td>{{ $room->name }}</td>
                        <td>{{ $room->type ?: '-' }}</td>
                        <td>৳ {{ number_format($room->price, 2) }}</td>
                        <td>
                            <span class="status status-{{ $room->status }}">{{ $room->status }}</span>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('rooms.show', $room) }}" class="btn btn-secondary">View</a>
                                <a href="{{ route('rooms.edit', $room) }}" class="btn btn-warning">Edit</a>

                                <form method="POST" action="{{ route('rooms.destroy', $room) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="btn btn-danger"
                                        onclick="return confirm('Delete this room?')"
                                    >
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="muted">No rooms found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>