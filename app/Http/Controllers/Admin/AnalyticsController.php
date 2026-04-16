<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmbeddedVideo;
use App\Models\User;
use App\Models\Video;

class AnalyticsController extends Controller
{
    public function index()
    {
        $topVideos = Video::query()->orderByDesc('views')->take(8)->get(['id', 'title', 'views', 'created_at']);

        $topCategories = EmbeddedVideo::query()
            ->selectRaw('COALESCE(category, "Uncategorized") as category, COUNT(*) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->take(8)
            ->get();

        $traffic = [
            'labels' => collect(range(6, 0))->map(fn ($offset) => now()->subDays($offset)->format('D'))->values(),
            'uploads' => collect(range(6, 0))->map(fn ($offset) => Video::whereDate('created_at', now()->subDays($offset))->count())->values(),
            'users' => collect(range(6, 0))->map(fn ($offset) => User::whereDate('created_at', now()->subDays($offset))->count())->values(),
        ];

        return view('admin.analytics.index', compact('topVideos', 'topCategories', 'traffic'));
    }
}
