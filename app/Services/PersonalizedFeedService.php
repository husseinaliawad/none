<?php

namespace App\Services;

use App\Models\EmbeddedVideo;
use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Collection;

class PersonalizedFeedService
{
    /**
     * Lightweight recommendation baseline using weighted tags overlap.
     * This is ready to be upgraded later with embeddings/LLM ranking.
     */
    public function buildForUser(?User $user, int $limit = 24): Collection
    {
        $baseVideos = Video::query()->latest()->take(80)->get();
        $baseEmbedded = EmbeddedVideo::query()->where('status', 'published')->latest('published_at')->take(80)->get();

        $scored = collect();

        foreach ($baseVideos as $video) {
            $score = (int) optional($video->tagsCloud)->sum('pivot.score');
            $scored->push([
                'type' => 'video',
                'item' => $video,
                'score' => $score,
            ]);
        }

        foreach ($baseEmbedded as $video) {
            $score = (int) optional($video->tagsCloud)->sum('pivot.score');
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
    }
}

