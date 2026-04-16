@extends('layouts.app')

@section('content')
<section>
    <div class="mb-5 flex items-center justify-between">
        <h1 class="text-xl font-bold text-white sm:text-2xl">Search Results</h1>
        <span class="text-sm text-muted">{{ is_countable($videos) ? count($videos) : 0 }} videos</span>
    </div>

    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6">
        @forelse ($videos as $video)
            <x-video-card :video="$video" />
        @empty
            <p class="col-span-full rounded-xl border border-white/10 bg-panel p-5 text-sm text-muted">No videos found for this query.</p>
        @endforelse
    </div>
</section>
@endsection
