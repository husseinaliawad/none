@extends('layouts.app')

@section('category-menu')
<div class="scrollbar-hide flex gap-2 overflow-x-auto whitespace-nowrap pb-1">
    <a href="{{ url('/') }}" class="rounded-full border border-white/15 bg-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-wide text-white">All</a>
    @foreach($categories as $category)
        <a href="{{ route('search', ['query' => $category]) }}" class="rounded-full border border-white/10 px-4 py-1.5 text-xs font-semibold uppercase tracking-wide text-gray-300 hover:border-white/25 hover:bg-white/10 hover:text-white">
            {{ $category }}
        </a>
    @endforeach
</div>
@endsection

@section('content')
<section class="space-y-8">
    <section>
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-bold tracking-wide text-white sm:text-xl">Embedded Videos</h2>
            <span class="text-xs uppercase tracking-wide text-muted">Imported</span>
        </div>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse($embeddedVideos as $video)
                <a href="{{ route('embed.watch', $video) }}" class="group block overflow-hidden rounded-xl border border-white/10 bg-panel/80 transition-all duration-200 hover:-translate-y-1 hover:shadow-glow">
                    <div class="relative aspect-video overflow-hidden bg-slate-900">
                        <img
                            src="{{ $video->thumbnail_url ?: 'https://placehold.co/640x360/111827/9ca3af?text=Play+Embedded+Video' }}"
                            alt="{{ $video->title }}"
                            loading="lazy"
                            onerror="this.onerror=null;this.src='https://placehold.co/640x360/111827/9ca3af?text=Play+Embedded+Video';"
                            class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                        >
                        <div class="absolute inset-0 flex items-center justify-center bg-black/35 transition group-hover:bg-black/25">
                            <span class="inline-flex h-14 w-14 items-center justify-center rounded-full border border-white/60 bg-black/40 text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="p-3">
                        <h3 class="truncate text-sm font-semibold text-white" title="{{ $video->title }}">{{ $video->title }}</h3>
                        <p class="mt-1 text-xs text-muted">{{ $video->source_name }}</p>
                    </div>
                </a>
            @empty
                <p class="col-span-full rounded-xl border border-white/10 bg-panel p-5 text-sm text-muted">No embedded videos available.</p>
            @endforelse
        </div>
    </section>

    <section>
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-bold tracking-wide text-white sm:text-xl">Trending Videos</h2>
            <span class="text-xs uppercase tracking-wide text-muted">Now Hot</span>
        </div>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6">
            @forelse($trendingVideos as $video)
                <x-video-card :video="$video" />
            @empty
                <p class="col-span-full rounded-xl border border-white/10 bg-panel p-5 text-sm text-muted">No trending videos available.</p>
            @endforelse
        </div>
    </section>

    <section>
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-bold tracking-wide text-white sm:text-xl">Latest Videos</h2>
            <span class="text-xs uppercase tracking-wide text-muted">Fresh Uploads</span>
        </div>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6">
            @forelse($latestVideos as $video)
                <x-video-card :video="$video" />
            @empty
                <p class="col-span-full rounded-xl border border-white/10 bg-panel p-5 text-sm text-muted">No latest videos available.</p>
            @endforelse
        </div>
    </section>

    <section>
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-bold tracking-wide text-white sm:text-xl">Recommended Videos</h2>
            <span class="text-xs uppercase tracking-wide text-muted">Suggested For You</span>
        </div>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6">
            @forelse($recommendedVideos as $video)
                <x-video-card :video="$video" />
            @empty
                <p class="col-span-full rounded-xl border border-white/10 bg-panel p-5 text-sm text-muted">No recommended videos available.</p>
            @endforelse
        </div>
    </section>
</section>
@endsection
