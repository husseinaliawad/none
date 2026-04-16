@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <div class="lg:col-span-8 xl:col-span-9">
            <div class="overflow-hidden rounded-2xl border border-white/10 bg-black">
                <div class="aspect-video">
                    <iframe src="{{ $video->embed_url }}" class="h-full w-full" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>

            <div class="mt-4 rounded-2xl border border-white/10 bg-panel p-4 sm:p-5">
                <h1 class="text-xl font-bold text-white sm:text-2xl">{{ $video->title }}</h1>
                <p class="mt-2 text-sm text-muted">
                    {{ $video->source_name }}{{ $video->published_at ? ' • ' . $video->published_at->diffForHumans() : '' }}
                </p>
                @if($video->description)
                    <p class="mt-4 text-sm text-gray-300">{{ $video->description }}</p>
                @endif
            </div>
        </div>

        <aside class="lg:col-span-4 xl:col-span-3">
            <div class="rounded-2xl border border-white/10 bg-panel p-3">
                <h3 class="mb-3 px-1 text-sm font-semibold uppercase tracking-wide text-gray-300">More Embedded Videos</h3>
                <div class="space-y-3">
                    @forelse($relatedVideos as $related)
                        <a href="{{ route('embed.watch', $related) }}" class="block rounded-xl border border-white/10 p-3 transition hover:bg-white/5">
                            <p class="truncate text-sm font-semibold text-white" title="{{ $related->title }}">{{ $related->title }}</p>
                            <p class="mt-1 text-xs text-muted">{{ $related->source_name }}</p>
                        </a>
                    @empty
                        <p class="rounded-lg border border-white/10 p-3 text-sm text-muted">No related embedded videos found.</p>
                    @endforelse
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection

