<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;

class EmbeddedVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail_url',
        'embed_url',
        'storyboard_vtt_url',
        'storyboard_sprite_url',
        'preview_timeline',
        'source_name',
        'source_video_id',
        'category',
        'tags',
        'status',
        'published_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tags' => 'array',
        'preview_timeline' => 'array',
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $video): void {
            if (blank($video->slug)) {
                $video->slug = Str::slug($video->title);
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function performers(): MorphToMany
    {
        return $this->morphToMany(Performer::class, 'performerable')
            ->withPivot('role_name')
            ->withTimestamps();
    }

    public function tagsCloud(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable')
            ->withPivot('score')
            ->withTimestamps();
    }
}
