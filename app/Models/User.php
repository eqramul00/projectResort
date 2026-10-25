<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function guest(): HasOne
    {
        return $this->hasOne(Guest::class);
    }

    public function isGuest(): bool
    {
        return $this->role === 'guest';
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function canViewBookings(): bool
    {
        return $this->hasAnyRole([
            'admin',
            'manager',
            'reception',
        ]);
    }

    public function canManageBookings(): bool
    {
        return $this->hasAnyRole([
            'admin',
            'manager',
        ]);
    }
}