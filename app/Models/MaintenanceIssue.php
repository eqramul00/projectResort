<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property Carbon $starts_on
 * @property Carbon $ends_on
 * @property Carbon|null $resolved_at
 * @property bool $blocks_booking
 */
class MaintenanceIssue extends Model
{
    protected $fillable = [
        'room_id',
        'created_by',
        'title',
        'description',
        'status',
        'blocks_booking',
        'starts_on',
        'ends_on',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'blocks_booking' => 'boolean',
            'starts_on' => 'date',
            'ends_on' => 'date',
            'resolved_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<Room, $this> */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /** @return BelongsTo<User, $this> */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}