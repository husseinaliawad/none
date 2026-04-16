<?php

namespace App\Services;

use App\Models\EmbeddedVideo;
use App\Models\VideoImportFailure;
use App\Models\VideoImportLog;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class VideoImportService
{
    public function __construct(protected EmbedSourceValidator $embedSourceValidator)
    {
    }

    public function importRecords(Collection $records, VideoImportLog $importLog, ?int $userId = null): void
    {
        $importLog->update([
            'status' => 'processing',
            'total_records' => $records->count(),
            'started_at' => now(),
            'error_message' => null,
        ]);

        $imported = 0;
        $failed = 0;

        foreach ($records as $index => $record) {
            $rowNumber = $index + 1;

            try {
                $this->importRow($record, $userId);
                $imported++;
            } catch (Throwable $exception) {
                $failed++;

                VideoImportFailure::create([
                    'video_import_log_id' => $importLog->id,
                    'row_number' => $rowNumber,
                    'payload' => is_array($record) ? $record : ['value' => $record],
                    'error_message' => $exception->getMessage(),
                ]);
            }
        }

        $status = $failed > 0
            ? ($imported > 0 ? 'completed_with_errors' : 'failed')
            : 'completed';

        $importLog->update([
            'status' => $status,
            'imported_records' => $imported,
            'failed_records' => $failed,
            'completed_at' => now(),
            'error_message' => $status === 'failed' ? 'No records were imported successfully.' : null,
        ]);
    }

    protected function importRow(array $record, ?int $userId = null): EmbeddedVideo
    {
        $title = trim((string) Arr::get($record, 'title', ''));
        $embedInput = (string) Arr::get($record, 'embed_url', '');

        if ($title === '' || trim($embedInput) === '') {
            throw new \InvalidArgumentException('title and embed_url are required fields.');
        }

        $normalized = $this->embedSourceValidator->normalizeAndValidate($embedInput);

        $sourceVideoId = trim((string) Arr::get($record, 'source_video_id', $normalized['source_video_id'] ?? ''));
        $sourceVideoId = $sourceVideoId !== '' ? $sourceVideoId : null;

        $duplicateQuery = EmbeddedVideo::query()->where('embed_url', $normalized['embed_url']);

        if ($sourceVideoId !== null) {
            $duplicateQuery->orWhere(function ($query) use ($sourceVideoId, $normalized) {
                $query->where('source_name', $normalized['source_name'])
                    ->where('source_video_id', $sourceVideoId);
            });
        }

        if ($duplicateQuery->exists()) {
            throw new \InvalidArgumentException('Duplicate video detected by embed_url or source_video_id.');
        }

        $slug = trim((string) Arr::get($record, 'slug', ''));
        if ($slug === '') {
            $slug = Str::slug($title);
        }

        $slug = $this->makeUniqueSlug($slug);

        $status = Arr::get($record, 'status', config('video_sources.default_status', 'draft'));
        $status = in_array($status, ['draft', 'published'], true) ? $status : 'draft';

        $tags = Arr::get($record, 'tags');
        if (is_string($tags)) {
            $tags = collect(explode(',', $tags))->map(fn ($tag) => trim($tag))->filter()->values()->all();
        }
        if (! is_array($tags)) {
            $tags = [];
        }

        $publishedAt = Arr::get($record, 'published_at');
        if ($status === 'published') {
            $publishedAt = $publishedAt ? Carbon::parse($publishedAt) : now();
        } else {
            $publishedAt = null;
        }

        return DB::transaction(function () use ($record, $title, $slug, $status, $tags, $publishedAt, $normalized, $sourceVideoId, $userId) {
            return EmbeddedVideo::create([
                'title' => $title,
                'slug' => $slug,
                'description' => Arr::get($record, 'description'),
                'thumbnail_url' => Arr::get($record, 'thumbnail_url'),
                'embed_url' => $normalized['embed_url'],
                'source_name' => trim((string) Arr::get($record, 'source_name', $normalized['source_name'])),
                'source_video_id' => $sourceVideoId,
                'category' => Arr::get($record, 'category'),
                'tags' => $tags,
                'status' => $status,
                'published_at' => $publishedAt,
                'created_by' => $userId,
                'updated_by' => $userId,
            ]);
        });
    }

    public function makeUniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $base = Str::slug($slug);
        $candidate = $base;
        $counter = 1;

        while (EmbeddedVideo::query()
            ->where('slug', $candidate)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $candidate = $base . '-' . $counter;
            $counter++;
        }

        return $candidate;
    }
}
