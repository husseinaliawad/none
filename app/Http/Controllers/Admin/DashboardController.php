<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\EmbeddedVideo;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoImportLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->startOfDay();
        $days = collect(range(13, 0))->map(fn ($offset) => Carbon::today()->subDays($offset));

        $totalVideos = Video::count() + EmbeddedVideo::count();
        $publishedVideos = Video::where('visibility', 'public')->count() + EmbeddedVideo::where('status', 'published')->count();
        $pendingVideos = max($totalVideos - $publishedVideos, 0);

        $kpis = [
            'total_users' => User::count(),
            'total_videos' => $totalVideos,
            'published_videos' => $publishedVideos,
            'pending_videos' => $pendingVideos,
            'views_today' => (int) Video::whereDate('updated_at', $today)->sum('views'),
            'comments_today' => Comment::whereDate('created_at', $today)->count(),
        ];

        $chart = [
            'labels' => $days->map(fn ($day) => $day->format('M d'))->values(),
            'daily_views' => $days->map(fn ($day) => (int) Video::whereDate('created_at', $day)->sum('views'))->values(),
            'uploads' => $days->map(fn ($day) => Video::whereDate('created_at', $day)->count() + EmbeddedVideo::whereDate('created_at', $day)->count())->values(),
            'new_users' => $days->map(fn ($day) => User::whereDate('created_at', $day)->count())->values(),
        ];

        $recentUploads = Video::with('channel')->latest()->take(6)->get();
        $recentEmbedded = EmbeddedVideo::latest()->take(6)->get();

        $topCategories = EmbeddedVideo::query()
            ->selectRaw('COALESCE(category, "Uncategorized") as category, COUNT(*) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        $recentActivity = collect()
            ->merge(User::latest()->take(4)->get()->map(fn ($item) => [
                'type' => 'user',
                'title' => $item->name . ' joined the platform',
                'meta' => $item->email,
                'time' => $item->created_at,
            ]))
            ->merge(Comment::with('user')->latest()->take(4)->get()->map(fn ($item) => [
                'type' => 'comment',
                'title' => optional($item->user)->name . ' posted a comment',
                'meta' => str($item->body)->limit(72),
                'time' => $item->created_at,
            ]))
            ->merge(VideoImportLog::latest()->take(4)->get()->map(fn ($item) => [
                'type' => 'import',
                'title' => 'Import #' . $item->id . ' is ' . $item->status,
                'meta' => strtoupper($item->source_type) . ' • ' . $item->source_reference,
                'time' => $item->created_at,
            ]))
            ->sortByDesc('time')
            ->take(10)
            ->values();

        $moderation = [
            'draft_videos' => EmbeddedVideo::where('status', 'draft')->count(),
            'unprocessed_videos' => Video::where('processed', false)->count(),
            'recent_comments' => Comment::whereDate('created_at', '>=', now()->subDays(2))->count(),
        ];

        return view('admin.dashboard', compact(
            'kpis',
            'chart',
            'recentUploads',
            'recentEmbedded',
            'topCategories',
            'recentActivity',
            'moderation'
        ));
    }
}
