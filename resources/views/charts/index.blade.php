@extends('layouts.app')

@section('content')
@php
    $performersCount = $topPerformers->count();
    $tagsCount = $topTags->count();
    $shortsCount = $hotShorts->count();
    $embeddedCount = $topEmbedded->count();
@endphp

<section class="space-y-6">
    <div class="rounded-2xl border border-white/10 bg-gradient-to-br from-[#141821] via-[#0f1118] to-[#0b0d12] p-5 shadow-[0_14px_38px_rgba(0,0,0,0.45)] sm:p-7">
        <p class="inline-flex rounded-full border border-white/15 bg-white/5 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] text-white/80">Live Dashboard</p>
        <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-white sm:text-4xl">Erotic Charts</h1>
        <p class="mt-2 max-w-3xl text-sm text-white/70 sm:text-base">Live ranking for performers, tags, and hot shorts with a cleaner hierarchy and faster scan flow.</p>
    </div>

    <div class="grid gap-6 lg:grid-cols-12">
        <div class="space-y-6 lg:col-span-8">
            <section class="rounded-2xl border border-white/10 bg-[#12151d] p-4 shadow-[0_10px_30px_rgba(0,0,0,0.35)] sm:p-5">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <h2 class="text-xl font-bold text-white">Top Performers</h2>
                    <span class="rounded-full border border-white/15 bg-white/5 px-3 py-1 text-xs font-semibold text-white/80">{{ $performersCount }} ranked</span>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    @forelse($topPerformers as $performer)
                        <a
                            href="{{ route('performers.show', $performer) }}"
                            class="group rounded-xl border border-white/10 bg-[#0f131a] p-3.5 transition duration-200 hover:-translate-y-0.5 hover:border-red-500/40 hover:bg-[#171c27] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-500/60"
                        >
                            <p class="text-sm font-semibold text-white">{{ $loop->iteration }}. {{ $performer->name }}</p>
                            <p class="mt-1 text-xs text-white/65">{{ $performer->embedded_videos_count + $performer->videos_count }} videos</p>
                        </a>
                    @empty
                        <p class="rounded-xl border border-white/10 bg-[#0f131a] p-4 text-sm text-white/70 sm:col-span-2">No performer data yet.</p>
                    @endforelse
                </div>
            </section>

            <section class="rounded-2xl border border-white/10 bg-[#12151d] p-4 shadow-[0_10px_30px_rgba(0,0,0,0.35)] sm:p-5">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <h2 class="text-xl font-bold text-white">Hot Shorts</h2>
                    <span class="rounded-full border border-white/15 bg-white/5 px-3 py-1 text-xs font-semibold text-white/80">{{ $shortsCount }} trending</span>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    @forelse($hotShorts as $clip)
                        <a
                            href="{{ route('shorts.index') }}"
                            class="group rounded-xl border border-white/10 bg-[#0f131a] p-3.5 transition duration-200 hover:-translate-y-0.5 hover:border-red-500/40 hover:bg-[#171c27] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-500/60"
                        >
                            <p class="text-sm font-semibold text-white">{{ $clip->title }}</p>
                            <p class="mt-1 text-xs text-white/65">Score: {{ number_format($clip->highlight_score, 2) }}</p>
                        </a>
                    @empty
                        <p class="rounded-xl border border-white/10 bg-[#0f131a] p-4 text-sm text-white/70 sm:col-span-2">No shorts chart yet.</p>
                    @endforelse
                </div>
            </section>
        </div>

        <aside class="space-y-6 lg:col-span-4">
            <section class="rounded-2xl border border-white/10 bg-[#12151d] p-4 shadow-[0_10px_30px_rgba(0,0,0,0.35)] sm:p-5">
                <h2 class="text-lg font-bold text-white">Chart Snapshot</h2>
                <div class="mt-4 grid grid-cols-2 gap-3">
                    <div class="rounded-xl border border-white/10 bg-[#0f131a] p-3">
                        <p class="text-[11px] uppercase tracking-wide text-white/60">Performers</p>
                        <p class="mt-1 text-xl font-bold text-white">{{ $performersCount }}</p>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-[#0f131a] p-3">
                        <p class="text-[11px] uppercase tracking-wide text-white/60">Tags</p>
                        <p class="mt-1 text-xl font-bold text-white">{{ $tagsCount }}</p>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-[#0f131a] p-3">
                        <p class="text-[11px] uppercase tracking-wide text-white/60">Hot Shorts</p>
                        <p class="mt-1 text-xl font-bold text-white">{{ $shortsCount }}</p>
                    </div>
                    <div class="rounded-xl border border-white/10 bg-[#0f131a] p-3">
                        <p class="text-[11px] uppercase tracking-wide text-white/60">Imported Videos</p>
                        <p class="mt-1 text-xl font-bold text-white">{{ $embeddedCount }}</p>
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-white/10 bg-[#12151d] p-4 shadow-[0_10px_30px_rgba(0,0,0,0.35)] sm:p-5">
                <h2 class="text-lg font-bold text-white">Top Tags</h2>
                <div class="mt-4 flex flex-wrap gap-2">
                    @forelse($topTags as $tag)
                        <span class="rounded-full border border-white/15 bg-[#0f131a] px-3 py-1.5 text-xs font-medium text-white/90 transition hover:border-red-500/45 hover:bg-[#1a202c]">
                            {{ $tag->name }} ({{ $tag->weight }})
                        </span>
                    @empty
                        <p class="text-sm text-white/70">No tags yet.</p>
                    @endforelse
                </div>
            </section>
        </aside>
    </div>
</section>
@endsection
