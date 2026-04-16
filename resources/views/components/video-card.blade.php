@props([
    'video',
    'showMeta' => true,
])

@php
    $thumbnail = $video->thumbnail_image
        ? asset('videos/' . $video->uid . '/' . $video->thumbnail_image)
        : 'https://placehold.co/640x360/111827/9ca3af?text=No+Thumbnail';

    $duration = $video->duration ?: '00:00';
@endphp

<a href="{{ route('video.watch', $video) }}" class="group block">
    <article class="overflow-hidden rounded-2xl border border-white/10 bg-panel/80 shadow-card transition-all duration-300 group-hover:-translate-y-1.5 group-hover:border-white/20 group-hover:shadow-glow">
        <div class="relative aspect-video overflow-hidden bg-slate-900">
            <img
                src="{{ $thumbnail }}"
                alt="{{ $video->title }}"
                loading="lazy"
                onerror="this.onerror=null;this.src='https://placehold.co/640x360/111827/9ca3af?text=No+Thumbnail';"
                class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-black/35 via-transparent to-transparent"></div>
            <span class="absolute bottom-2 right-2 rounded bg-black/80 px-2 py-1 text-xs font-semibold text-white">
                {{ $duration }}
            </span>
        </div>

        <div class="p-3.5">
            <h3 class="max-h-10 overflow-hidden text-sm font-semibold leading-5 text-white" title="{{ $video->title }}">{{ $video->title }}</h3>

            @if($showMeta)
                <div class="mt-2 flex items-center justify-between gap-2">
                    <p class="text-xs text-muted">{{ number_format($video->views ?? 0) }} views</p>
                    <p class="rounded-full border border-white/10 px-2 py-0.5 text-[11px] text-gray-300">{{ $video->created_at?->diffForHumans() }}</p>
                </div>
            @endif
        </div>
    </article>
</a>
