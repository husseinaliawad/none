@props([
    'title' => 'No data available',
    'description' => 'Add content to get started.',
])

<div class="rounded-2xl border border-dashed border-admin-border bg-admin-cardSoft/60 p-8 text-center">
    <div class="mx-auto mb-3 h-10 w-10 rounded-xl bg-admin-accent/20"></div>
    <h4 class="text-base font-semibold text-white">{{ $title }}</h4>
    <p class="mt-2 text-sm text-admin-muted">{{ $description }}</p>
</div>
