<?php

namespace App\Http\Controllers;

use App\Models\EmbeddedVideo;
use App\Services\UserProgressService;
use Illuminate\View\View;

class EmbeddedWatchController extends Controller
{
    public function __construct(protected UserProgressService $userProgressService)
    {
    }

    public function show(EmbeddedVideo $video): View
    {
        $video->loadMissing('performers');
        $relatedVideos = EmbeddedVideo::query()
            ->where('id', '!=', $video->id)
            ->where('status', 'published')
            ->latest('published_at')
            ->take(10)
            ->get();

        if (auth()->check()) {
            $this->userProgressService->trackWatchSession(auth()->user(), 90);
        }

        return view('embed.watch', compact('video', 'relatedVideos'));
    }
}
