<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GuestAuthController extends Controller
{
    public function showRegister(Request $request)
    {
        if ($request->user()) {
            return $this->redirectAuthenticatedUser($request);
        }

        return view('guest.auth', [
            'register' => true,
        ]);
    }

    public function register(Request $request)
    {
        if ($request->user()) {
            return $this->redirectAuthenticatedUser($request);
        }

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'phone' => [
                'required',
                'string',
                'max:50',
            ],
            'password' => [
                'required',
                'string',
                'min:12',
                'max:128',
                'confirmed',
            ],
        ]);

        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => 'guest',
            ]);

            $user->guest()->create([
                'full_name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
            ]);

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->route('guest.dashboard')
            ->with('success', 'Your guest account has been created.');
    }

    public function showLogin(Request $request)
    {
        if ($request->user()) {
            return $this->redirectAuthenticatedUser($request);
        }

        return view('guest.auth', [
            'register' => false,
        ]);
    }

    public function login(Request $request)
    {
        if ($request->user()) {
            return $this->redirectAuthenticatedUser($request);
        }

        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials = [
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => 'guest',
        ];

        if (!Auth::attempt($credentials)) {
            return back()
                ->withErrors([
                    'email' => 'The email or password is incorrect.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->route('guest.dashboard');
    }

    public function dashboard(Request $request)
    {
        $guest = $request->user()->guest;

        abort_unless(
            $guest,
            403,
            'Your guest profile could not be found. Please contact reception.'
        );

        // Query only bookings belonging to this account's guest profile.
        $bookings = $guest->bookings()
            ->with('room')
            ->latest()
            ->paginate(10);

        return view('guest.dashboard', compact('guest', 'bookings'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('guest.login')
            ->with('success', 'You have been logged out.');
    }

    private function redirectAuthenticatedUser(Request $request)
    {
        return redirect()->route(
            $request->user()->isGuest()
                ? 'guest.dashboard'
                : 'dashboard'
        );
    }
}