@extends('layouts.app')

@section('content')
<section>
    <div class="mb-5 flex items-center justify-between">
        <h1 class="text-xl font-bold text-white sm:text-2xl">Search Results</h1>
        <span class="text-sm text-muted">
            {{ (is_countable($videos) ? count($videos) : 0) + (is_countable($embeddedVideos ?? []) ? count($embeddedVideos) : 0) }} items
        </span>
    </div>

    <div class="mb-6">
        <h2 class="mb-3 text-base font-semibold text-white">Embedded Results</h2>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse (($embeddedVideos ?? []) as $video)
                <a href="{{ route('embed.watch', $video) }}" class="block overflow-hidden rounded-xl border border-white/10 bg-panel/80 hover:-translate-y-1 hover:shadow-glow">
                    <img src="{{ $video->thumbnail_url ?: 'https://placehold.co/640x360/111827/9ca3af?text=Embedded' }}" class="aspect-video w-full object-cover" alt="{{ $video->title }}">
                    <div class="p-3">
                        <h3 class="truncate text-sm font-semibold text-white">{{ $video->title }}</h3>
                        <p class="mt-1 text-xs text-muted">{{ $video->source_name }}</p>
                    </div>
                </a>
            @empty
                <p class="col-span-full rounded-xl border border-white/10 bg-panel p-5 text-sm text-muted">No embedded videos found for this query.</p>
            @endforelse
        </div>
    </div>

    <h2 class="mb-3 text-base font-semibold text-white">Channel Videos</h2>
    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6">
        @forelse ($videos as $video)
            <x-video-card :video="$video" />
        @empty
            <p class="col-span-full rounded-xl border border-white/10 bg-panel p-5 text-sm text-muted">No videos found for this query.</p>
        @endforelse
    </div>
</section>
@endsection
