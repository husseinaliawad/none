<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Video;
use App\Models\EmbeddedVideo;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EmbeddedVideoController as AdminEmbeddedVideoController;
use App\Http\Controllers\Admin\VideoImportController as AdminVideoImportController;
use App\Http\Controllers\Admin\UserManagementController as AdminUserManagementController;
use App\Http\Controllers\Admin\CommentManagementController as AdminCommentManagementController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\AnalyticsController as AdminAnalyticsController;
use App\Http\Controllers\Admin\ModulePagesController as AdminModulePagesController;
use App\Http\Controllers\PerformerController;
use App\Http\Controllers\EmbeddedWatchController;
use App\Http\Controllers\FanGroupController;
use App\Http\Controllers\ChartsController;
use App\Http\Controllers\ShortsController;
use App\Services\PersonalizedFeedService;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $forYouFeed = app(PersonalizedFeedService::class)->buildForUser(auth()->user(), 18);

    try {
        $embeddedVideos = EmbeddedVideo::query()
            ->where('status', 'published')
            ->latest('published_at')
            ->take(18)
            ->get();
    } catch (\Throwable $e) {
        report($e);
        $embeddedVideos = collect();
    }

    try {
        // if logged in show channels that user subscribed to
        if (Auth::check()) {
            $channels = Auth::user()->subscribedChannels()->with('videos')->get()->pluck('videos');
        } else {
            //else show all videos
            $channels = App\Models\Channel::get()->pluck('videos');
        }

        $videos = $channels->flatten()->filter()->unique('id')->values();

        if ($videos->isEmpty()) {
            $videos = Video::query()->with('channel')->latest()->take(60)->get();
        }

        $trendingVideos = $videos->sortByDesc('views')->take(18)->values();
        $latestVideos = $videos->sortByDesc('created_at')->take(24)->values();
        $recommendedVideos = $videos->shuffle()->take(18)->values();
        $categories = $videos
            ->map(fn ($video) => optional($video->channel)->name)
            ->filter()
            ->unique()
            ->take(20)
            ->values();
    } catch (\Throwable $e) {
        report($e);
        $channels = collect();
        $videos = collect();
        $trendingVideos = collect();
        $latestVideos = collect();
        $recommendedVideos = collect();
        $categories = collect();
    }

    return view('welcome', compact(
        'channels',
        'videos',
        'forYouFeed',
        'trendingVideos',
        'latestVideos',
        'recommendedVideos',
        'embeddedVideos',
        'categories'
    ));
});

Route::get('/healthz', fn () => response('ok', 200));

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/channel/{channel}/edit', [App\Http\Controllers\ChannelController::class, 'edit'])->name('channel.edit');

    Route::get('/videos/{channel}/create', 'App\Http\Livewire\Video\CreateVideo')->name('video.create');
    Route::get('/videos/{channel}/{video}/edit', 'App\Http\Livewire\Video\EditVideo')->name('video.edit');
    Route::get('/videos/{channel}', 'App\Http\Livewire\Video\AllVideo')->name('video.all');
});

Route::get('/watch/{video}', 'App\Http\Livewire\Video\WatchVideo')->name('video.watch');
Route::get('/watch/embed/{video:slug}', [EmbeddedWatchController::class, 'show'])->name('embed.watch');

Route::get('/channels/{channel}', [App\Http\Controllers\ChannelController::class, 'index'])->name('channel.index');

Route::get('/search/', [App\Http\Controllers\SearchController::class, 'search'])->name('search');
Route::get('/performers/{performer:slug}', [PerformerController::class, 'show'])->name('performers.show');
Route::get('/fan-groups', [FanGroupController::class, 'index'])->name('fan-groups.index');
Route::get('/fan-groups/{group:slug}', [FanGroupController::class, 'show'])->name('fan-groups.show');
Route::post('/fan-groups/{group:slug}/join', [FanGroupController::class, 'join'])->name('fan-groups.join');
Route::get('/charts', [ChartsController::class, 'index'])->name('charts.index');
Route::get('/shorts', [ShortsController::class, 'index'])->name('shorts.index');

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin,editor'])
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::post('/videos/bulk-action', [AdminEmbeddedVideoController::class, 'bulkAction'])->name('videos.bulk-action');
        Route::post('/videos/preview', [AdminEmbeddedVideoController::class, 'preview'])->name('videos.preview');
        Route::patch('/videos/{video}/publish', [AdminEmbeddedVideoController::class, 'publish'])->name('videos.publish');
        Route::patch('/videos/{video}/unpublish', [AdminEmbeddedVideoController::class, 'unpublish'])->name('videos.unpublish');
        Route::resource('videos', AdminEmbeddedVideoController::class);

        Route::get('/users', [AdminUserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [AdminUserManagementController::class, 'show'])->name('users.show');

        Route::get('/comments', [AdminCommentManagementController::class, 'index'])->name('comments.index');
        Route::delete('/comments/{comment}', [AdminCommentManagementController::class, 'destroy'])->name('comments.destroy');

        Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');

        Route::get('/imports', [AdminVideoImportController::class, 'index'])->name('imports.index');
        Route::post('/imports', [AdminVideoImportController::class, 'store'])->name('imports.store');
        Route::get('/imports/{import}', [AdminVideoImportController::class, 'show'])->name('imports.show');

        Route::get('/analytics', [AdminAnalyticsController::class, 'index'])->name('analytics.index');
        Route::resource('performers', \App\Http\Controllers\Admin\PerformerController::class)->except('show');
        Route::get('/categories', [AdminModulePagesController::class, 'categories'])->name('categories.index');
        Route::get('/tags', [AdminModulePagesController::class, 'tags'])->name('tags.index');
        Route::get('/reports', [AdminModulePagesController::class, 'reports'])->name('reports.index');
        Route::get('/ads', [AdminModulePagesController::class, 'ads'])->name('ads.index');
        Route::get('/roles', [AdminModulePagesController::class, 'roles'])->name('roles.index');
    });
