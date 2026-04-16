@extends('layouts.app')

@section('content')
<section class="space-y-6">
    <div class="rounded-2xl border border-white/10 bg-panel/80 p-4 sm:p-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
            <img
                src="{{ $performer->avatar_url ?: 'https://placehold.co/160x160/111827/9ca3af?text=Performer' }}"
                alt="{{ $performer->name }}"
                class="h-20 w-20 rounded-xl object-cover sm:h-24 sm:w-24"
            >
            <div>
                <h1 class="text-2xl font-bold text-white">{{ $performer->name }}</h1>
                @if($performer->country)
                    <p class="mt-1 text-sm text-muted">{{ $performer->country }}</p>
                @endif
                @if($performer->bio)
                    <p class="mt-3 text-sm text-gray-300">{{ $performer->bio }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-white/10 bg-panel/80 p-4 sm:p-6">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">Videos by Date</h2>
            <span class="text-xs uppercase tracking-wide text-muted">{{ $items->count() }} items</span>
        </div>

        <div class="space-y-2">
            @forelse($items as $item)
                <a href="{{ $item['url'] }}" class="flex items-center justify-between gap-3 rounded-xl border border-white/10 px-3 py-3 transition hover:bg-white/5">
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-white">{{ $item['title'] }}</p>
                        <p class="mt-1 text-xs text-muted">{{ $item['source'] }}</p>
                    </div>
                    <div class="shrink-0 text-right">
                        <p class="text-[11px] uppercase tracking-wide text-gray-400">{{ strtoupper($item['type']) }}</p>
                        <p class="mt-1 text-xs text-muted">{{ optional($item['published_at'])->format('Y-m-d') }}</p>
                    </div>
                </a>
            @empty
                <p class="rounded-xl border border-white/10 bg-black/20 p-4 text-sm text-muted">No videos linked for this performer yet.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection

