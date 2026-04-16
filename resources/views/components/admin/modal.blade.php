@props(['name' => 'modal'])

<div x-data="{ open: false }" x-on:open-{{ $name }}.window="open = true" x-on:keydown.escape.window="open = false">
    {{ $trigger ?? '' }}

    <div x-show="open" class="fixed inset-0 z-[80] flex items-center justify-center bg-black/60 p-4" x-transition.opacity>
        <div class="w-full max-w-lg rounded-2xl border border-admin-border bg-admin-card p-5 shadow-lift" x-transition>
            {{ $slot }}
            <div class="mt-4 text-right">
                <button type="button" class="rounded-lg border border-admin-border px-3 py-1.5 text-sm text-white" @click="open = false">Close</button>
            </div>
        </div>
    </div>
</div>
