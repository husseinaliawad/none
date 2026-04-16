@extends('layouts.app')

@section('content')
<section class="space-y-6">
    <div class="rounded-2xl border border-white/10 bg-panel/80 p-4 sm:p-6">
        <h1 class="text-2xl font-bold text-white">Shorts</h1>
        <p class="mt-2 text-sm text-muted">Highlights from trending videos and embedded content.</p>
    </div>

    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($clips as $clip)
            <article class="rounded-xl border border-white/10 bg-panel p-4">
                <p class="text-sm font-semibold text-white">{{ $clip->title }}</p>
                <p class="mt-1 text-xs text-muted">Range: {{ $clip->start_seconds }}s - {{ $clip->end_seconds }}s</p>
                <p class="mt-1 text-xs text-muted">Score: {{ number_format($clip->highlight_score, 2) }}</p>
                @if($clip->embeddedVideo)
                    <a href="{{ route('embed.watch', $clip->embeddedVideo) }}" class="mt-3 inline-flex rounded-lg border border-white/15 px-3 py-1.5 text-xs text-white">Watch Source</a>
                @elseif($clip->video)
                    <a href="{{ route('video.watch', $clip->video) }}" class="mt-3 inline-flex rounded-lg border border-white/15 px-3 py-1.5 text-xs text-white">Watch Source</a>
                @endif
            </article>
        @empty
            <p class="rounded-xl border border-white/10 bg-panel p-4 text-sm text-muted">No shorts available yet.</p>
        @endforelse
    </div>

    <div>{{ $clips->links() }}</div>
</section>
@endsection

