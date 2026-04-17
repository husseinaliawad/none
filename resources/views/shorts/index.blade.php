@extends('layouts.app')

@section('content')
<section class="space-y-7">
    <div class="rounded-2xl border border-white/10 bg-gradient-to-br from-[#151926] via-[#0f131d] to-[#0b0d12] p-5 shadow-[0_14px_38px_rgba(0,0,0,0.45)] sm:p-6">
        <h1 class="text-2xl font-extrabold tracking-tight text-white sm:text-3xl">Shorts</h1>
        <p class="mt-2 text-sm text-white/70">Vertical quick clips, sorted by highlight score.</p>
    </div>

    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6">
        @forelse($clips as $clip)
            @php
                $source = $clip->embeddedVideo;
                $video = $clip->video;
                $link = $source ? route('embed.watch', $source) : ($video ? route('video.watch', $video) : route('shorts.index'));
                $thumb = $source
                    ? ($source->resolved_thumbnail_url ?: 'https://placehold.co/640x1138/111827/9ca3af?text=Short')
                    : ($video && $video->thumbnail_image
                        ? asset('videos/' . $video->uid . '/' . $video->thumbnail_image)
                        : 'https://placehold.co/640x1138/111827/9ca3af?text=Short');
            @endphp

            <a href="{{ $link }}" class="group block">
                <article class="overflow-hidden rounded-2xl border border-white/10 bg-[#151922] shadow-[0_10px_30px_rgba(0,0,0,0.35)] transition duration-300 group-hover:-translate-y-1 group-hover:border-red-500/40">
                    <div class="relative aspect-[9/16] overflow-hidden bg-black">
                        <img
                            src="{{ $thumb }}"
                            alt="{{ $clip->title }}"
                            loading="lazy"
                            onerror="this.onerror=null;this.src='https://placehold.co/640x1138/111827/9ca3af?text=Short';"
                            class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                        >
                        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/85 via-black/20 to-transparent px-3 pb-3 pt-12">
                            <p class="max-h-10 overflow-hidden text-sm font-semibold leading-5 text-white">{{ $clip->title }}</p>
                        </div>
                    </div>

                    <div class="px-3 py-2.5">
                        <p class="text-[11px] text-white/70">Score: {{ number_format($clip->highlight_score, 2) }}</p>
                        <p class="mt-1 text-[11px] text-white/55">{{ (int) $clip->start_seconds }}s - {{ (int) $clip->end_seconds }}s</p>
                    </div>
                </article>
            </a>
        @empty
            <p class="col-span-full rounded-xl border border-white/10 bg-[#12151d] p-4 text-sm text-white/70">No shorts available yet.</p>
        @endforelse
    </div>

    <div class="pt-1">{{ $clips->links() }}</div>
</section>
@endsection
