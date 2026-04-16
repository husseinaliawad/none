@extends('admin.layouts.app', ['pageTitle' => 'Video Management'])

@section('content')
<div class="space-y-5">
    <x-admin.section-header title="Video Library" subtitle="Moderate, publish, and organize embedded videos from approved sources.">
        <x-slot name="actions">
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.imports.index') }}" class="rounded-xl border border-admin-border bg-admin-cardSoft px-4 py-2 text-sm font-semibold text-white hover:bg-white/10">Import Center</a>
                <a href="{{ route('admin.videos.create') }}" class="rounded-xl bg-admin-accent px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">Add Video</a>
            </div>
        </x-slot>
    </x-admin.section-header>

    <x-admin.panel>
        <form method="GET" class="grid gap-3 md:grid-cols-5">
            <input name="search" value="{{ request('search') }}" placeholder="Search title, slug, source" class="rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2 text-sm text-white placeholder:text-admin-muted focus:border-admin-accent focus:outline-none md:col-span-2">
            <select name="status" class="rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2 text-sm text-white focus:border-admin-accent focus:outline-none">
                <option value="">All Status</option>
                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                <option value="published" @selected(request('status') === 'published')>Published</option>
            </select>
            <select name="category" class="rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2 text-sm text-white focus:border-admin-accent focus:outline-none">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                @endforeach
            </select>
            <div class="flex gap-2">
                <button class="flex-1 rounded-xl bg-white/10 px-3 py-2 text-sm font-semibold text-white hover:bg-white/20">Filter</button>
                <a href="{{ route('admin.videos.index') }}" class="rounded-xl border border-admin-border px-3 py-2 text-sm text-admin-muted hover:text-white">Reset</a>
            </div>
        </form>
    </x-admin.panel>

    <form method="POST" action="{{ route('admin.videos.bulk-action') }}" class="space-y-4" x-data="{ selectedAll: false }">
        @csrf
        <x-admin.panel>
            <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <select name="action" class="rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2 text-sm text-white" required>
                        <option value="">Bulk Action</option>
                        <option value="publish">Publish</option>
                        <option value="unpublish">Unpublish</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button class="rounded-xl bg-admin-accent px-3 py-2 text-sm font-semibold text-white hover:bg-blue-500">Apply</button>
                </div>
                <p class="text-xs text-admin-muted">{{ $videos->total() }} total results</p>
            </div>

            @if($videos->count())
                <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($videos as $video)
                        <article class="rounded-2xl border border-admin-border bg-admin-cardSoft p-3 transition hover:-translate-y-0.5 hover:border-admin-accent/40 hover:shadow-soft">
                            <div class="mb-3 flex items-start gap-3">
                                <input type="checkbox" name="ids[]" value="{{ $video->id }}" class="mt-2 h-4 w-4 rounded border-admin-border bg-admin-base text-admin-accent">
                                <img src="{{ $video->thumbnail_url ?: 'https://placehold.co/320x180/0d0d16/8f94b3?text=Thumbnail' }}" class="h-20 w-32 rounded-lg object-cover" alt="{{ $video->title }}">
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('admin.videos.show', $video) }}" class="block truncate text-sm font-semibold text-white hover:text-admin-accent">{{ $video->title }}</a>
                                    <p class="mt-1 text-xs text-admin-muted">{{ $video->source_name }} • {{ $video->category ?: 'Uncategorized' }}</p>
                                    <p class="mt-1 text-xs text-admin-muted">{{ $video->published_at?->diffForHumans() ?? 'Not published' }}</p>
                                    <div class="mt-2 flex items-center gap-2">
                                        <x-admin.badge :value="$video->status" />
                                        @if($video->source_video_id)
                                            <span class="rounded-full border border-admin-border px-2 py-1 text-[10px] text-admin-muted">{{ str($video->source_video_id)->limit(10) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between gap-2 border-t border-admin-border pt-3">
                                <a href="{{ route('admin.videos.edit', $video) }}" class="rounded-lg border border-admin-border px-3 py-1.5 text-xs text-white hover:bg-white/10">Edit</a>
                                <x-admin.action-menu>
                                    <div class="space-y-1 text-xs">
                                        <a href="{{ route('admin.videos.show', $video) }}" class="block rounded-lg px-2 py-1.5 text-admin-muted hover:bg-white/10 hover:text-white">View Details</a>
                                        @if($video->status === 'draft')
                                            <form method="POST" action="{{ route('admin.videos.publish', $video) }}">@csrf @method('PATCH')<button class="w-full rounded-lg px-2 py-1.5 text-left text-admin-muted hover:bg-white/10 hover:text-white">Publish</button></form>
                                        @else
                                            <form method="POST" action="{{ route('admin.videos.unpublish', $video) }}">@csrf @method('PATCH')<button class="w-full rounded-lg px-2 py-1.5 text-left text-admin-muted hover:bg-white/10 hover:text-white">Unpublish</button></form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.videos.destroy', $video) }}" onsubmit="return confirm('Delete this video?');">@csrf @method('DELETE')<button class="w-full rounded-lg px-2 py-1.5 text-left text-rose-300 hover:bg-rose-500/20">Delete</button></form>
                                    </div>
                                </x-admin.action-menu>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <x-admin.empty-state title="No videos found" description="Try changing filters or add your first embedded video." />
            @endif
        </x-admin.panel>

        <div>{{ $videos->links() }}</div>
    </form>
</div>
@endsection
