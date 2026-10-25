<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserRoleController extends Controller
{
    private array $staffRoles = [
        'admin',
        'manager',
        'reception',
    ];

    public function index(Request $request)
    {
        $this->ensureAdmin($request);

        $users = User::whereIn('role', $this->staffRoles)
            ->whereDoesntHave('guest')
            ->orderBy('name')
            ->get();

        return view('users.roles', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        $this->ensureAdmin($request);

        $data = $request->validate([
            'role' => [
                'required',
                'in:admin,manager,reception',
            ],
        ]);

        DB::transaction(function () use ($request, $user, $data) {
            $current = User::whereKey($user->id)
                ->lockForUpdate()
                ->firstOrFail();

            // Reject guest accounts, including requests made directly
            // to the role-update endpoint.
            if (
                !in_array($current->role, $this->staffRoles, true)
                || $current->guest()->exists()
            ) {
                throw ValidationException::withMessages([
                    'role' => 'Guest accounts cannot be changed through staff role management.',
                ]);
            }

            if (
                (int) $request->user()->id === (int) $current->id
                && $data['role'] !== 'admin'
            ) {
                throw ValidationException::withMessages([
                    'role' => 'You cannot remove your own administrator access.',
                ]);
            }

            $current->update([
                'role' => $data['role'],
            ]);
        }, 5);

        return back()->with(
            'success',
            'Staff role updated successfully.'
        );
    }

    private function ensureAdmin(Request $request): void
    {
        abort_unless(
            $request->user()
                && $request->user()->role === 'admin',
            403,
            'Only administrators can manage staff roles.'
        );
    }
}