@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <div class="lg:col-span-8 xl:col-span-9">
            <div class="overflow-hidden rounded-2xl border border-white/10 bg-black shadow-card">
                <div class="aspect-video">
                    <iframe src="{{ $video->embed_url }}" class="h-full w-full" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>

            <div class="mt-4 rounded-2xl border border-white/10 bg-panel/90 p-4 shadow-card sm:p-5">
                <h1 class="font-display text-xl font-bold text-white sm:text-2xl">{{ $video->title }}</h1>
                <p class="mt-2 text-sm text-muted">
                    {{ $video->source_name }}{{ $video->published_at ? ' - ' . $video->published_at->diffForHumans() : '' }}
                </p>
                @auth
                    <p class="mt-2 text-xs text-emerald-300">
                        Rank: {{ optional(auth()->user()->progress)->current_level ?? 1 }} -
                        Points: {{ optional(auth()->user()->progress)->points ?? 0 }}
                    </p>
                @endauth
                @if($video->description)
                    <p class="mt-4 text-sm text-gray-300">{{ $video->description }}</p>
                @endif
                @if($video->performers->isNotEmpty())
                    <p class="mt-3 text-xs text-muted">
                        Performers:
                        @foreach($video->performers as $performer)
                            <a href="{{ route('performers.show', $performer) }}" class="text-emerald-300 hover:text-emerald-200">{{ $performer->name }}</a>@if(!$loop->last), @endif
                        @endforeach
                    </p>
                @endif
                @if(is_array($video->tags) && count($video->tags))
                    <div class="mt-3 flex flex-wrap gap-1">
                        @foreach($video->tags as $tag)
                            <span class="rounded-full border border-white/15 px-2 py-1 text-[11px] text-gray-200">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif
            </div>

            @if(is_array($video->preview_timeline) && count($video->preview_timeline))
                <div class="rounded-2xl border border-white/10 bg-panel/90 p-4 shadow-card sm:p-5">
                    <h2 class="mb-3 font-display text-base font-semibold text-white">Storyboard Preview</h2>
                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-4 lg:grid-cols-6">
                        @foreach($video->preview_timeline as $frame)
                            <div class="rounded-lg border border-white/10 bg-black/20 p-2">
                                <img src="{{ $frame['thumbnail'] ?? 'https://placehold.co/320x180/111827/9ca3af?text=Frame' }}" class="aspect-video w-full rounded object-cover" alt="Frame">
                                <p class="mt-1 text-[11px] text-muted">{{ $frame['at'] ?? '0s' }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <aside class="lg:col-span-4 xl:col-span-3">
            <div class="rounded-2xl border border-white/10 bg-panel/90 p-3 shadow-card">
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
