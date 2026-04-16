@props(['label' => 'Actions'])

<div class="relative" x-data="{ open: false }">
    <button type="button" class="rounded-lg border border-admin-border bg-admin-cardSoft px-3 py-1.5 text-xs font-semibold text-white hover:bg-white/10" @click="open = !open">
        {{ $label }}
    </button>
    <div x-show="open" x-transition @click.outside="open = false" class="absolute right-0 z-20 mt-2 min-w-[10rem] rounded-xl border border-admin-border bg-admin-card p-2 shadow-lift">
        {{ $slot }}
    </div>
</div>
