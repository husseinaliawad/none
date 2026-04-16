@extends('admin.layouts.app', ['pageTitle' => 'Video Detail'])

@section('content')
<div class="space-y-5">
    <x-admin.section-header :title="$video->title" subtitle="Full metadata and preview">
        <x-slot name="actions">
            <div class="flex gap-2">
                <a href="{{ route('admin.videos.edit', $video) }}" class="rounded-xl border border-admin-border bg-admin-cardSoft px-4 py-2 text-sm text-white hover:bg-white/10">Edit</a>
                <a href="{{ route('admin.videos.index') }}" class="rounded-xl border border-admin-border bg-admin-cardSoft px-4 py-2 text-sm text-white hover:bg-white/10">Back</a>
            </div>
        </x-slot>
    </x-admin.section-header>

    <div class="grid gap-4 xl:grid-cols-3">
        <x-admin.panel class="xl:col-span-2" title="Video Preview">
            <div class="aspect-video overflow-hidden rounded-xl border border-admin-border bg-black">
                <iframe src="{{ $video->embed_url }}" class="h-full w-full" frameborder="0" allowfullscreen></iframe>
            </div>
            <p class="mt-3 text-xs text-admin-muted">{{ $video->embed_url }}</p>
        </x-admin.panel>

        <x-admin.panel title="Metadata">
            <dl class="space-y-3 text-sm">
                <div><dt class="text-admin-muted">Status</dt><dd class="mt-1"><x-admin.badge :value="$video->status" /></dd></div>
                <div><dt class="text-admin-muted">Source</dt><dd class="mt-1 text-white">{{ $video->source_name }}</dd></div>
                <div><dt class="text-admin-muted">Source Video ID</dt><dd class="mt-1 text-white">{{ $video->source_video_id ?: 'N/A' }}</dd></div>
                <div><dt class="text-admin-muted">Category</dt><dd class="mt-1 text-white">{{ $video->category ?: 'Uncategorized' }}</dd></div>
                <div><dt class="text-admin-muted">Tags</dt><dd class="mt-1 flex flex-wrap gap-1">@forelse($video->tags ?? [] as $tag)<span class="rounded-full border border-admin-border px-2 py-1 text-xs">{{ $tag }}</span>@empty<span class="text-admin-muted">No tags</span>@endforelse</dd></div>
                <div><dt class="text-admin-muted">Created</dt><dd class="mt-1 text-white">{{ $video->created_at }}</dd></div>
                <div><dt class="text-admin-muted">Published</dt><dd class="mt-1 text-white">{{ $video->published_at ?: 'Not published' }}</dd></div>
            </dl>
        </x-admin.panel>
    </div>

    <x-admin.panel title="Similar Videos">
        @if($similarVideos->count())
            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                @foreach($similarVideos as $item)
                    <a href="{{ route('admin.videos.show', $item) }}" class="rounded-xl border border-admin-border bg-admin-cardSoft p-3 hover:border-admin-accent/40">
                        <p class="truncate text-sm font-semibold text-white">{{ $item->title }}</p>
                        <p class="mt-1 text-xs text-admin-muted">{{ $item->source_name }} • {{ $item->category ?: 'Uncategorized' }}</p>
                    </a>
                @endforeach
            </div>
        @else
            <x-admin.empty-state title="No similar videos" description="No videos found in this category." />
        @endif
    </x-admin.panel>
</div>
@endsection
