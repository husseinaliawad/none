@extends('layouts.app')

@section('content')
<section class="space-y-7">
    <div class="rounded-2xl border border-white/10 bg-gradient-to-br from-[#151926] via-[#0f131d] to-[#0b0d12] p-5 shadow-[0_14px_38px_rgba(0,0,0,0.45)] sm:p-6">
        <h1 class="text-2xl font-extrabold tracking-tight text-white sm:text-3xl">GIFs</h1>
        <p class="mt-2 text-sm text-white/70">Classic feed layout with quick previews and instant playback links.</p>
    </div>

    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">Embedded GIF Feed</h2>
            <span class="text-xs text-white/65">{{ $embeddedVideos->count() }} items</span>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse($embeddedVideos as $video)
                <a href="{{ route('embed.watch', $video) }}" class="group block overflow-hidden rounded-2xl border border-white/10 bg-[#151922] shadow-[0_10px_30px_rgba(0,0,0,0.35)] transition hover:-translate-y-1 hover:border-red-500/40">
                    <div class="relative aspect-video overflow-hidden bg-black">
                        <img
                            src="{{ $video->resolved_thumbnail_url ?: 'https://placehold.co/640x360/111827/9ca3af?text=Preview' }}"
                            alt="{{ $video->title }}"
                            loading="lazy"
                            onerror="this.onerror=null;this.src='https://placehold.co/640x360/111827/9ca3af?text=Preview';"
                            class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                        >
                    </div>
                    <div class="p-3">
                        <h3 class="max-h-10 overflow-hidden text-sm font-semibold text-white">{{ $video->title }}</h3>
                        <p class="mt-1 text-xs text-white/65">{{ $video->source_name ?: 'Embedded Source' }}</p>
                    </div>
                </a>
            @empty
                <p class="col-span-full rounded-xl border border-white/10 bg-[#12151d] p-4 text-sm text-white/70">No embedded gifs found.</p>
            @endforelse
        </div>
    </div>

    <div class="space-y-3">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">Channel Video Feed</h2>
            <span class="text-xs text-white/65">{{ $videos->count() }} items</span>
        </div>

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6">
            @forelse($videos as $video)
                <x-video-card :video="$video" />
            @empty
                <p class="col-span-full rounded-xl border border-white/10 bg-[#12151d] p-4 text-sm text-white/70">No videos found.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
