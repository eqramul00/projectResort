<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    public function index()
    {
        abort_unless(auth()->check() && auth()->user()->role === 'admin', 403);

        $users = User::orderBy('name')->get();

        return view('users.roles', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        abort_unless(auth()->check() && auth()->user()->role === 'admin', 403);

        $data = $request->validate([
            'role' => ['required', 'in:admin,manager,reception'],
        ]);

        if ($user->email === 'backup@resort.com') {
            return back()->with('error', 'Backup admin role cannot be changed.');
        }

        $user->update([
            'role' => $data['role'],
        ]);

        return back()->with('success', 'User role updated.');
    }
}