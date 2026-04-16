@props([
    'title',
    'subtitle' => null,
    'actions' => null,
])

<div class="mb-4 flex flex-wrap items-end justify-between gap-3">
    <div>
        <h2 class="text-lg font-bold text-white sm:text-xl">{{ $title }}</h2>
        @if($subtitle)
            <p class="mt-1 text-sm text-admin-muted">{{ $subtitle }}</p>
        @endif
    </div>
    @if($actions)
        <div>{{ $actions }}</div>
    @endif
</div>
