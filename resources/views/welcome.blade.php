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
    @php
        $userLevel = (int) optional(optional(auth()->user())->progress)->current_level;
    @endphp

    <section class="rounded-3xl border border-white/10 bg-gradient-to-br from-white/10 via-white/5 to-transparent p-5 shadow-card sm:p-7">
        <div class="grid gap-6 lg:grid-cols-12 lg:items-center">
            <div class="lg:col-span-8">
                <p class="mb-2 inline-flex rounded-full border border-white/15 px-3 py-1 text-[11px] uppercase tracking-[0.18em] text-gray-300">Premium Discovery</p>
                <h1 class="font-display text-3xl font-bold leading-tight text-white sm:text-4xl">Modern Feed, Personalized Ranking, Better Watch Experience</h1>
                <p class="mt-3 max-w-2xl text-sm text-gray-300 sm:text-base">
                    Discover trending drops, curated picks, and creator-focused collections in one clean interface optimized for fast browsing.
                </p>
            </div>
            <div class="lg:col-span-4">
                <div class="grid grid-cols-2 gap-2">
                    <div class="rounded-2xl border border-white/10 bg-black/30 p-3">
                        <p class="text-[11px] uppercase tracking-wide text-muted">For You</p>
                        <p class="mt-1 text-xl font-bold text-white">{{ count($forYouFeed ?? []) }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-black/30 p-3">
                        <p class="text-[11px] uppercase tracking-wide text-muted">Embedded</p>
                        <p class="mt-1 text-xl font-bold text-white">{{ count($embeddedVideos ?? []) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="rounded-2xl border border-white/10 bg-panel/70 p-4 sm:p-5">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-bold text-white">Level Unlocks</h2>
                <p class="mt-1 text-xs text-muted">
                    @auth
                        Your level: {{ max($userLevel, 1) }} - Keep watching to unlock more content.
                    @else
                        Sign in to start leveling up and unlock exclusive drops.
                    @endauth
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('charts.index') }}" class="rounded-lg border border-white/15 px-3 py-2 text-xs font-semibold text-white hover:bg-white/5">Open Charts</a>
                <a href="{{ route('shorts.index') }}" class="rounded-lg border border-white/15 px-3 py-2 text-xs font-semibold text-white hover:bg-white/5">Open Shorts</a>
            </div>
        </div>
    </section>

    <section>
        <div class="mb-4 flex items-center justify-between">
            <h2 class="font-display text-lg font-bold tracking-wide text-white sm:text-xl">For You</h2>
            <span class="text-xs uppercase tracking-wide text-muted">AI Ranked</span>
        </div>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse($forYouFeed as $row)
                @php
                    $item = $row['item'];
                    $isEmbedded = $row['type'] === 'embedded';
                    $url = $isEmbedded ? route('embed.watch', $item) : route('video.watch', $item);
                    $thumbnail = $isEmbedded
                        ? ($item->thumbnail_url ?: 'https://placehold.co/640x360/111827/9ca3af?text=Embedded')
                        : ($item->thumbnail_image ? asset('videos/' . $item->uid . '/' . $item->thumbnail_image) : 'https://placehold.co/640x360/111827/9ca3af?text=No+Thumbnail');
                @endphp
                <a href="{{ $url }}" class="block overflow-hidden rounded-2xl border border-white/10 bg-panel/80 shadow-card transition hover:-translate-y-1.5 hover:border-white/20 hover:shadow-glow">
                    <img src="{{ $thumbnail }}" class="aspect-video w-full object-cover" alt="{{ $item->title }}">
                    <div class="p-3">
                        <h3 class="max-h-10 overflow-hidden text-sm font-semibold text-white">{{ $item->title }}</h3>
                        <p class="mt-1 text-xs text-muted">{{ $isEmbedded ? $item->source_name : optional($item->channel)->name }}</p>
                    </div>
                </a>
            @empty
                <p class="col-span-full rounded-xl border border-white/10 bg-panel p-5 text-sm text-muted">No personalized items yet.</p>
            @endforelse
        </div>
    </section>

    <section>
        <div class="mb-4 flex items-center justify-between">
            <h2 class="font-display text-lg font-bold tracking-wide text-white sm:text-xl">Embedded Videos</h2>
            <span class="text-xs uppercase tracking-wide text-muted">Imported</span>
        </div>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse($embeddedVideos as $video)
                <a href="{{ route('embed.watch', $video) }}" class="group block overflow-hidden rounded-2xl border border-white/10 bg-panel/80 shadow-card transition-all duration-300 hover:-translate-y-1.5 hover:border-white/20 hover:shadow-glow">
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
                        <h3 class="max-h-10 overflow-hidden text-sm font-semibold text-white" title="{{ $video->title }}">{{ $video->title }}</h3>
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
            <h2 class="font-display text-lg font-bold tracking-wide text-white sm:text-xl">Trending Videos</h2>
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
            <h2 class="font-display text-lg font-bold tracking-wide text-white sm:text-xl">Latest Videos</h2>
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
            <h2 class="font-display text-lg font-bold tracking-wide text-white sm:text-xl">Recommended Videos</h2>
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
