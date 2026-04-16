<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphedByMany;
use Illuminate\Support\Str;

class Performer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'bio',
        'avatar_url',
        'birth_date',
        'country',
        'is_active',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $performer): void {
            if (blank($performer->slug)) {
                $performer->slug = Str::slug($performer->name);
            }
        });
    }

    public function videos(): MorphedByMany
    {
        return $this->morphedByMany(Video::class, 'performerable')
            ->withPivot('role_name')
            ->withTimestamps();
    }

    public function embeddedVideos(): MorphedByMany
    {
        return $this->morphedByMany(EmbeddedVideo::class, 'performerable')
            ->withPivot('role_name')
            ->withTimestamps();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
