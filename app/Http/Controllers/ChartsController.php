<?php

namespace App\Http\Controllers;

use App\Models\EmbeddedVideo;
use App\Models\Performer;
use App\Models\ShortClip;
use App\Models\Tag;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ChartsController extends Controller
{
    public function index(): View
    {
        try {
            $topEmbedded = EmbeddedVideo::query()
                ->when(Schema::hasColumn('embedded_videos', 'status'), fn ($q) => $q->where('status', 'published'))
                ->when(Schema::hasColumn('embedded_videos', 'published_at'), fn ($q) => $q->latest('published_at'), fn ($q) => $q->latest())
                ->take(12)
                ->get();
        } catch (\Throwable $e) {
            report($e);
            $topEmbedded = collect();
        }

        try {
            $topPerformers = Schema::hasTable('performers')
                ? Performer::query()
                    ->withCount(['embeddedVideos', 'videos'])
                    ->get()
                    ->sortByDesc(fn ($item) => $item->embedded_videos_count + $item->videos_count)
                    ->take(12)
                    ->values()
                : collect();
        } catch (\Throwable $e) {
            report($e);
            $topPerformers = collect();
        }

        try {
            $topTags = Schema::hasTable('tags')
                ? Tag::query()->latest('weight')->take(20)->get()
                : collect();
        } catch (\Throwable $e) {
            report($e);
            $topTags = collect();
        }

        try {
            $hotShorts = Schema::hasTable('short_clips')
                ? ShortClip::query()
                    ->when(Schema::hasColumn('short_clips', 'status'), fn ($q) => $q->where('status', 'published'))
                    ->latest('highlight_score')
                    ->take(12)
                    ->get()
                : collect();
        } catch (\Throwable $e) {
            report($e);
            $hotShorts = collect();
        }

        return view('charts.index', compact('topEmbedded', 'topPerformers', 'topTags', 'hotShorts'));
    }
}
