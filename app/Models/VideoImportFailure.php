<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoImportFailure extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_import_log_id',
        'row_number',
        'payload',
        'error_message',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function importLog(): BelongsTo
    {
        return $this->belongsTo(VideoImportLog::class, 'video_import_log_id');
    }
}
