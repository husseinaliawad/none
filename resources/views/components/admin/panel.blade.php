@props([
    'title' => null,
    'padding' => 'p-4 sm:p-5',
])

<section {{ $attributes->class('rounded-2xl border border-admin-border bg-admin-card/95 shadow-soft') }}>
    @if($title)
        <header class="border-b border-admin-border px-4 py-3 sm:px-5">
            <h3 class="text-sm font-semibold uppercase tracking-wide text-admin-muted">{{ $title }}</h3>
        </header>
    @endif
    <div class="{{ $padding }}">
        {{ $slot }}
    </div>
</section>
