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
