<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShortClip extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'video_id',
        'embedded_video_id',
        'start_seconds',
        'end_seconds',
        'highlight_score',
        'status',
        'created_by',
    ];

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    public function embeddedVideo(): BelongsTo
    {
        return $this->belongsTo(EmbeddedVideo::class);
    }
}

