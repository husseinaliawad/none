<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmbeddedVideo;

class ModulePagesController extends Controller
{
    public function categories()
    {
        $categories = EmbeddedVideo::query()
            ->selectRaw('COALESCE(category, "Uncategorized") as category, COUNT(*) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function tags()
    {
        $tagCounts = EmbeddedVideo::query()
            ->get(['tags'])
            ->flatMap(fn ($video) => is_array($video->tags) ? $video->tags : [])
            ->map(fn ($tag) => trim((string) $tag))
            ->filter()
            ->countBy()
            ->sortDesc()
            ->take(60);

        return view('admin.tags.index', compact('tagCounts'));
    }

    public function reports()
    {
        return view('admin.reports.index');
    }

    public function ads()
    {
        return view('admin.ads.index');
    }

    public function roles()
    {
        return view('admin.roles.index');
    }
}
