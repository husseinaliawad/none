<?php

namespace App\Http\Controllers;

use App\Models\EmbeddedVideo;
use App\Models\Performer;
use App\Models\ShortClip;
use App\Models\Tag;
use Illuminate\View\View;

class ChartsController extends Controller
{
    public function index(): View
    {
        $topEmbedded = EmbeddedVideo::query()
            ->where('status', 'published')
            ->latest('published_at')
            ->take(12)
            ->get();

        $topPerformers = Performer::query()
            ->withCount(['embeddedVideos', 'videos'])
            ->get()
            ->sortByDesc(fn ($item) => $item->embedded_videos_count + $item->videos_count)
            ->take(12)
            ->values();

        $topTags = Tag::query()
            ->latest('weight')
            ->take(20)
            ->get();

        $hotShorts = ShortClip::query()
            ->where('status', 'published')
            ->latest('highlight_score')
            ->take(12)
            ->get();

        return view('charts.index', compact('topEmbedded', 'topPerformers', 'topTags', 'hotShorts'));
    }
}

