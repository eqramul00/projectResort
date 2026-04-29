<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function index(Request $request)
{
    $query = Room::with('images');

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $rooms = $query->latest()->get();

    return view('rooms.index', compact('rooms'));
}

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'number' => ['required', 'unique:rooms,number'],
            'name' => ['required'],
            'type' => ['nullable'],
            'price' => ['required', 'numeric'],
            'status' => ['required'],
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $room = Room::create([
            'number' => $data['number'],
            'name' => $data['name'],
            'type' => $data['type'] ?? null,
            'price' => $data['price'],
            'status' => $data['status'],
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('rooms', 'public');

                $room->images()->create([
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('rooms.index')->with('success', 'Room created successfully.');
    }

    public function show(Room $room)
    {
        $room->load('images');
        return view('rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        $room->load('images');
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $data = $request->validate([
            'number' => ['required', 'unique:rooms,number,' . $room->id],
            'name' => ['required'],
            'type' => ['nullable'],
            'price' => ['required', 'numeric'],
            'status' => ['required'],
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer', 'exists:room_images,id'],
        ]);

        $room->update([
            'number' => $data['number'],
            'name' => $data['name'],
            'type' => $data['type'] ?? null,
            'price' => $data['price'],
            'status' => $data['status'],
        ]);

        if (!empty($data['delete_images'])) {
            $imagesToDelete = $room->images()->whereIn('id', $data['delete_images'])->get();

            foreach ($imagesToDelete as $img) {
                Storage::disk('public')->delete($img->image_path);
                $img->delete();
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('rooms', 'public');

                $room->images()->create([
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('rooms.index')->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        foreach ($room->images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }

        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully.');
    }
}