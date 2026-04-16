<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'points',
        'current_level',
        'total_watch_seconds',
        'streak_days',
        'last_watched_at',
    ];

    protected $casts = [
        'last_watched_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

