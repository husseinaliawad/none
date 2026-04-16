@props([
    'value' => 'info',
])

@php
    $palette = match($value) {
        'published', 'approved', 'active', 'completed' => 'bg-emerald-500/15 text-emerald-200 border-emerald-400/30',
        'draft', 'pending', 'processing' => 'bg-amber-500/15 text-amber-100 border-amber-400/30',
        'failed', 'hidden', 'inactive' => 'bg-rose-500/15 text-rose-100 border-rose-400/30',
        default => 'bg-white/10 text-gray-100 border-white/20'
    };
@endphp

<span {{ $attributes->class("inline-flex items-center rounded-full border px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide {$palette}") }}>
    {{ $slot->isEmpty() ? $value : $slot }}
</span>
