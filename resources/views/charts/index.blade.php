@extends('layouts.app')

@section('content')
<section class="space-y-8">
    <div class="rounded-2xl border border-white/10 bg-panel/80 p-4 sm:p-6">
        <h1 class="text-2xl font-bold text-white">Erotic Charts</h1>
        <p class="mt-2 text-sm text-muted">Live ranking for performers, tags, and hot shorts.</p>
    </div>

    <section>
        <h2 class="mb-3 text-lg font-semibold text-white">Top Performers</h2>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($topPerformers as $performer)
                <a href="{{ route('performers.show', $performer) }}" class="rounded-xl border border-white/10 bg-panel p-3 hover:bg-white/5">
                    <p class="text-sm font-semibold text-white">{{ $performer->name }}</p>
                    <p class="mt-1 text-xs text-muted">{{ $performer->embedded_videos_count + $performer->videos_count }} videos</p>
                </a>
            @empty
                <p class="rounded-xl border border-white/10 bg-panel p-4 text-sm text-muted">No performer data yet.</p>
            @endforelse
        </div>
    </section>

    <section>
        <h2 class="mb-3 text-lg font-semibold text-white">Top Tags</h2>
        <div class="flex flex-wrap gap-2">
            @forelse($topTags as $tag)
                <span class="rounded-full border border-white/15 bg-white/5 px-3 py-1 text-xs text-white">{{ $tag->name }} ({{ $tag->weight }})</span>
            @empty
                <p class="text-sm text-muted">No tags yet.</p>
            @endforelse
        </div>
    </section>

    <section>
        <h2 class="mb-3 text-lg font-semibold text-white">Hot Shorts</h2>
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($hotShorts as $clip)
                <a href="{{ route('shorts.index') }}" class="rounded-xl border border-white/10 bg-panel p-3 hover:bg-white/5">
                    <p class="text-sm font-semibold text-white">{{ $clip->title }}</p>
                    <p class="mt-1 text-xs text-muted">Score: {{ number_format($clip->highlight_score, 2) }}</p>
                </a>
            @empty
                <p class="rounded-xl border border-white/10 bg-panel p-4 text-sm text-muted">No shorts chart yet.</p>
            @endforelse
        </div>
    </section>
</section>
@endsection

