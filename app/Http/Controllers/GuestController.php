<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index(Request $request)
{
    $query = \App\Models\Guest::with([
        'bookings.room',
    ])->withCount('bookings');

    if ($request->filled('search')) {
        $search = trim($request->search);

        $query->where(function ($q) use ($search) {
            $q->where('phone', 'like', "%{$search}%")
              ->orWhere('full_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    $guests = $query->latest()->get();

    return view('guests.index', compact('guests'));
}
    public function create()
    {
        return view('guests.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required'],
            'phone' => ['required'],
            'email' => ['nullable', 'email'],
            'address' => ['nullable'],
        ]);

        Guest::create($data);

        return redirect()->route('guests.index')->with('success', 'Guest created');
    }

    public function show(Guest $guest)
{
    $guest->load(['bookings.room']);

    return view('guests.show', compact('guest'));
}

    public function edit(Guest $guest)
    {
        return view('guests.edit', compact('guest'));
    }

    public function update(Request $request, Guest $guest)
    {
        $data = $request->validate([
            'full_name' => ['required'],
            'phone' => ['required'],
            'email' => ['nullable', 'email'],
            'address' => ['nullable'],
        ]);

        $guest->update($data);

        return redirect()->route('guests.index')->with('success', 'Guest updated');
    }

    public function destroy(Guest $guest)
    {
        $guest->delete();

        return redirect()->route('guests.index')->with('success', 'Guest deleted');
    }
}