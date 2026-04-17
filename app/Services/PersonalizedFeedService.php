<?php

namespace App\Services;

use App\Models\EmbeddedVideo;
use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Throwable;

class PersonalizedFeedService
{
    /**
     * Lightweight recommendation baseline using weighted tags overlap.
     * This is ready to be upgraded later with embeddings/LLM ranking.
     */
    public function buildForUser(?User $user, int $limit = 24): Collection
    {
        try {
            $baseVideos = Schema::hasTable('videos')
                ? Video::query()->latest()->take(80)->get()
                : collect();

            $baseEmbedded = Schema::hasTable('embedded_videos')
                ? EmbeddedVideo::query()->where('status', 'published')->latest('published_at')->take(80)->get()
                : collect();

            $tagTablesReady = Schema::hasTable('taggables') && Schema::hasTable('tags');
            $scored = collect();

            foreach ($baseVideos as $video) {
                $score = $tagTablesReady ? (int) optional($video->tagsCloud)->sum('pivot.score') : 0;
                $scored->push([
                    'type' => 'video',
                    'item' => $video,
                    'score' => $score,
                ]);
            }

            foreach ($baseEmbedded as $video) {
                $score = $tagTablesReady ? (int) optional($video->tagsCloud)->sum('pivot.score') : 0;
                $scored->push([
                    'type' => 'embedded',
                    'item' => $video,
                    'score' => $score,
                ]);
            }

            // Current baseline: tag strength + freshness. Later: user/session vectors.
            return $scored
                ->sortByDesc(fn ($row) => $row['score'] + (int) optional($row['item']->created_at)->timestamp)
                ->take($limit)
                ->values();
        } catch (Throwable $e) {
            report($e);
            return collect();
        }
    }
}
