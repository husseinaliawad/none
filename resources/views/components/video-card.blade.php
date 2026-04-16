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
    <article class="overflow-hidden rounded-xl border border-white/10 bg-panel/80 transition-all duration-200 group-hover:-translate-y-1 group-hover:scale-[1.015] group-hover:shadow-glow">
        <div class="relative aspect-video overflow-hidden bg-slate-900">
            <img
                src="{{ $thumbnail }}"
                alt="{{ $video->title }}"
                loading="lazy"
                onerror="this.onerror=null;this.src='https://placehold.co/640x360/111827/9ca3af?text=No+Thumbnail';"
                class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
            >
            <span class="absolute bottom-2 right-2 rounded bg-black/80 px-2 py-1 text-xs font-semibold text-white">
                {{ $duration }}
            </span>
        </div>

        <div class="p-3">
            <h3 class="truncate text-sm font-semibold leading-5 text-white" title="{{ $video->title }}">{{ $video->title }}</h3>

            @if($showMeta)
                <p class="mt-2 text-xs text-muted">{{ number_format($video->views ?? 0) }} views</p>
                <p class="mt-1 text-xs text-muted">{{ $video->created_at?->diffForHumans() }}</p>
            @endif
        </div>
    </article>
</a>
