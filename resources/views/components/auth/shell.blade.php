@props([
    'title',
    'subtitle' => null,
])

<div class="mx-auto w-full max-w-md py-8 sm:py-12">
    <div class="overflow-hidden rounded-2xl border border-white/10 bg-[#12121a]/95 p-5 shadow-2xl sm:p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-extrabold text-white">{{ $title }}</h1>
            @if($subtitle)
                <p class="mt-2 text-sm text-gray-400">{{ $subtitle }}</p>
            @endif
        </div>

        {{ $slot }}
    </div>
</div>
