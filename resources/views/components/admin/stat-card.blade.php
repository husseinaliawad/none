@props([
    'title',
    'value' => 0,
    'hint' => null,
    'tone' => 'accent',
])

@php
    $tones = [
        'accent' => 'from-admin-accent/25 to-blue-500/10 border-admin-accent/30',
        'success' => 'from-emerald-500/20 to-emerald-700/10 border-emerald-400/30',
        'warning' => 'from-amber-500/20 to-orange-600/10 border-amber-300/30',
        'danger' => 'from-rose-500/20 to-rose-700/10 border-rose-400/30',
    ];
@endphp

<div class="rounded-2xl border {{ $tones[$tone] ?? $tones['accent'] }} bg-gradient-to-br p-4 shadow-soft transition hover:-translate-y-0.5 hover:shadow-lift">
    <p class="text-xs uppercase tracking-[0.15em] text-admin-muted">{{ $title }}</p>
    <p class="mt-3 text-2xl font-extrabold text-white">{{ $value }}</p>
    @if($hint)
        <p class="mt-2 text-xs text-admin-muted">{{ $hint }}</p>
    @endif
</div>
