<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class FanGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'performer_id',
        'name',
        'slug',
        'description',
        'is_private',
    ];

    protected $casts = [
        'is_private' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $group): void {
            if (blank($group->slug)) {
                $group->slug = Str::slug($group->name);
            }
        });
    }

    public function performer(): BelongsTo
    {
        return $this->belongsTo(Performer::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'fan_group_members')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
