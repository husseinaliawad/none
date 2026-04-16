<?php

namespace App\Http\Controllers;

use App\Models\Performer;
use Illuminate\View\View;

class PerformerController extends Controller
{
    public function show(Performer $performer): View
    {
        $videoItems = $performer->videos()
            ->latest('videos.created_at')
            ->get()
            ->map(fn ($video) => [
                'type' => 'video',
                'title' => $video->title,
                'url' => route('video.watch', $video),
                'source' => optional($video->channel)->name ?: 'Channel',
                'published_at' => $video->created_at,
            ]);

        $embeddedItems = $performer->embeddedVideos()
            ->where('status', 'published')
            ->latest('embedded_videos.published_at')
            ->get()
            ->map(fn ($video) => [
                'type' => 'embedded',
                'title' => $video->title,
                'url' => route('embed.watch', $video),
                'source' => $video->source_name,
                'published_at' => $video->published_at ?: $video->created_at,
            ]);

        $items = $videoItems
            ->concat($embeddedItems)
            ->sortByDesc('published_at')
            ->values();

        return view('performers.show', compact('performer', 'items'));
    }
}

