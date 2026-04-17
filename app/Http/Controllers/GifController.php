<?php

namespace App\Http\Controllers;

use App\Models\EmbeddedVideo;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GifController extends Controller
{
    public function index(Request $request): View
    {
        $query = trim((string) $request->query('q', ''));

        $videos = Video::query()
            ->with('channel')
            ->when($query !== '', function ($builder) use ($query): void {
                $builder->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->latest()
            ->take(36)
            ->get();

        $embeddedVideos = EmbeddedVideo::query()
            ->where('status', 'published')
            ->when($query !== '', function ($builder) use ($query): void {
                $builder->where(function ($inner) use ($query): void {
                    $inner->where('title', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orWhereJsonContains('tags', $query);
                });
            })
            ->latest('published_at')
            ->take(24)
            ->get();

        return view('gifs.index', compact('query', 'videos', 'embeddedVideos'));
    }
}

