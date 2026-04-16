<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BulkVideoActionRequest;
use App\Http\Requests\Admin\PreviewEmbedRequest;
use App\Http\Requests\Admin\StoreEmbeddedVideoRequest;
use App\Http\Requests\Admin\UpdateEmbeddedVideoRequest;
use App\Models\EmbeddedVideo;
use App\Models\Performer;
use App\Models\Tag;
use App\Services\EmbedSourceValidator;
use App\Services\VideoImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class EmbeddedVideoController extends Controller
{
    public function __construct(
        protected EmbedSourceValidator $embedSourceValidator,
        protected VideoImportService $videoImportService,
    ) {
    }

    public function index(Request $request)
    {
        $query = EmbeddedVideo::query()->latest();

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($innerQuery) use ($search): void {
                $innerQuery->where('title', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%')
                    ->orWhere('source_name', 'like', '%' . $search . '%')
                    ->orWhere('source_video_id', 'like', '%' . $search . '%');
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($source = $request->input('source_name')) {
            $query->where('source_name', $source);
        }

        $videos = $query->paginate(15)->withQueryString();
        $categories = EmbeddedVideo::query()->whereNotNull('category')->distinct()->orderBy('category')->pluck('category');
        $sources = EmbeddedVideo::query()->distinct()->orderBy('source_name')->pluck('source_name');

        return view('admin.videos.index', compact('videos', 'categories', 'sources'));
    }

    public function create()
    {
        $performers = Performer::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.videos.create', compact('performers'));
    }

    public function store(StoreEmbeddedVideoRequest $request): RedirectResponse
    {
        $normalized = $this->embedSourceValidator->normalizeAndValidate($request->string('embed_url')->toString());

        $sourceVideoId = trim((string) ($request->input('source_video_id') ?: $normalized['source_video_id']));
        $sourceVideoId = $sourceVideoId !== '' ? $sourceVideoId : null;

        $this->assertUniqueVideo($normalized['embed_url'], $sourceVideoId, $normalized['source_name']);

        $status = $request->input('status', 'draft');

        $video = EmbeddedVideo::create([
            'title' => $request->input('title'),
            'slug' => $this->videoImportService->makeUniqueSlug($request->input('slug') ?: Str::slug($request->input('title'))),
            'description' => $request->input('description'),
            'thumbnail_url' => $request->input('thumbnail_url'),
            'embed_url' => $normalized['embed_url'],
            'storyboard_vtt_url' => $request->input('storyboard_vtt_url'),
            'storyboard_sprite_url' => $request->input('storyboard_sprite_url'),
            'source_name' => $request->input('source_name') ?: $normalized['source_name'],
            'source_video_id' => $sourceVideoId,
            'category' => $request->input('category'),
            'tags' => $this->parseTags($request->input('tags')),
            'status' => $status,
            'published_at' => $status === 'published'
                ? ($request->input('published_at') ? Carbon::parse($request->input('published_at')) : now())
                : null,
            'created_by' => $request->user()->id,
            'updated_by' => $request->user()->id,
        ]);

        $this->syncTagsCloud($video, $request->input('tags'));
        $this->syncPerformers($video, $request->input('performer_ids', []));

        return redirect()->route('admin.videos.index')->with('status', 'Video created successfully.');
    }

    public function edit(EmbeddedVideo $video)
    {
        $video->loadMissing('performers');
        $performers = Performer::query()->where('is_active', true)->orderBy('name')->get();

        return view('admin.videos.edit', compact('video', 'performers'));
    }

    public function show(EmbeddedVideo $video)
    {
        $video->loadMissing('performers');
        $similarVideos = EmbeddedVideo::query()
            ->where('id', '!=', $video->id)
            ->when($video->category, fn ($query) => $query->where('category', $video->category))
            ->latest()
            ->take(6)
            ->get();

        return view('admin.videos.show', compact('video', 'similarVideos'));
    }

    public function update(UpdateEmbeddedVideoRequest $request, EmbeddedVideo $video): RedirectResponse
    {
        $normalized = $this->embedSourceValidator->normalizeAndValidate($request->string('embed_url')->toString());

        $sourceVideoId = trim((string) ($request->input('source_video_id') ?: $normalized['source_video_id']));
        $sourceVideoId = $sourceVideoId !== '' ? $sourceVideoId : null;

        $this->assertUniqueVideo($normalized['embed_url'], $sourceVideoId, $request->input('source_name') ?: $normalized['source_name'], $video->id);

        $status = $request->input('status', 'draft');

        $video->update([
            'title' => $request->input('title'),
            'slug' => $this->videoImportService->makeUniqueSlug($request->input('slug') ?: Str::slug($request->input('title')), $video->id),
            'description' => $request->input('description'),
            'thumbnail_url' => $request->input('thumbnail_url'),
            'embed_url' => $normalized['embed_url'],
            'storyboard_vtt_url' => $request->input('storyboard_vtt_url'),
            'storyboard_sprite_url' => $request->input('storyboard_sprite_url'),
            'source_name' => $request->input('source_name') ?: $normalized['source_name'],
            'source_video_id' => $sourceVideoId,
            'category' => $request->input('category'),
            'tags' => $this->parseTags($request->input('tags')),
            'status' => $status,
            'published_at' => $status === 'published'
                ? ($request->input('published_at') ? Carbon::parse($request->input('published_at')) : ($video->published_at ?: now()))
                : null,
            'updated_by' => $request->user()->id,
        ]);

        $this->syncTagsCloud($video, $request->input('tags'));
        $this->syncPerformers($video, $request->input('performer_ids', []));

        return redirect()->route('admin.videos.index')->with('status', 'Video updated successfully.');
    }

    public function destroy(EmbeddedVideo $video): RedirectResponse
    {
        $video->delete();

        return redirect()->route('admin.videos.index')->with('status', 'Video deleted successfully.');
    }

    public function publish(EmbeddedVideo $video): RedirectResponse
    {
        $video->update([
            'status' => 'published',
            'published_at' => $video->published_at ?: now(),
        ]);

        return back()->with('status', 'Video published successfully.');
    }

    public function unpublish(EmbeddedVideo $video): RedirectResponse
    {
        $video->update([
            'status' => 'draft',
            'published_at' => null,
        ]);

        return back()->with('status', 'Video unpublished successfully.');
    }

    public function bulkAction(BulkVideoActionRequest $request): RedirectResponse
    {
        $ids = $request->input('ids', []);
        $action = $request->input('action');

        $query = EmbeddedVideo::query()->whereIn('id', $ids);

        if ($action === 'publish') {
            $query->update(['status' => 'published', 'published_at' => now()]);
        }

        if ($action === 'unpublish') {
            $query->update(['status' => 'draft', 'published_at' => null]);
        }

        if ($action === 'delete') {
            $query->delete();
        }

        return back()->with('status', 'Bulk action executed successfully.');
    }

    public function preview(PreviewEmbedRequest $request)
    {
        $normalized = $this->embedSourceValidator->normalizeAndValidate($request->string('embed_url')->toString());

        return response()->json([
            'embed_url' => $normalized['embed_url'],
            'iframe' => sprintf('<iframe src="%s" width="640" height="360" frameborder="0" allowfullscreen></iframe>', e($normalized['embed_url'])),
            'source_name' => $normalized['source_name'],
            'source_video_id' => $normalized['source_video_id'],
        ]);
    }

    protected function parseTags(?string $tags): array
    {
        if (blank($tags)) {
            return [];
        }

        return collect(explode(',', $tags))
            ->map(fn (string $tag) => trim($tag))
            ->filter()
            ->values()
            ->all();
    }

    protected function assertUniqueVideo(string $embedUrl, ?string $sourceVideoId, string $sourceName, ?int $ignoreId = null): void
    {
        $duplicateByEmbed = EmbeddedVideo::query()
            ->where('embed_url', $embedUrl)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists();

        if ($duplicateByEmbed) {
            throw ValidationException::withMessages([
                'embed_url' => 'Duplicate video detected by embed URL.',
            ]);
        }

        if ($sourceVideoId === null) {
            return;
        }

        $duplicateBySource = EmbeddedVideo::query()
            ->where('source_name', $sourceName)
            ->where('source_video_id', $sourceVideoId)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists();

        if ($duplicateBySource) {
            throw ValidationException::withMessages([
                'source_video_id' => 'Duplicate video detected by source_video_id.',
            ]);
        }
    }

    protected function syncTagsCloud(EmbeddedVideo $video, ?string $tags): void
    {
        $parsed = $this->parseTags($tags);
        $sync = [];

        foreach ($parsed as $name) {
            $tag = Tag::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'weight' => 1]
            );

            $sync[$tag->id] = ['score' => 1];
        }

        $video->tagsCloud()->sync($sync);
    }

    protected function syncPerformers(EmbeddedVideo $video, array $performerIds): void
    {
        $sync = collect($performerIds)
            ->filter()
            ->mapWithKeys(fn ($id) => [(int) $id => ['role_name' => 'performer']])
            ->all();

        $video->performers()->sync($sync);
    }
}
