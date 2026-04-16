<div class="my-4 rounded-xl border border-white/10 bg-black/20 p-4">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <img src="{{ $channel->picture }}" class="h-16 w-16 rounded-full object-cover" alt="{{ $channel->name }}">
            <div>
                <h4 class="text-base font-semibold text-white">{{ $channel->name }}</h4>
                <p class="text-sm text-muted">{{ number_format($channel->subscribers()) }} subscribers</p>
            </div>
        </div>

        <button wire:click.prevent="toggle" class="rounded-lg px-4 py-2 text-sm font-semibold uppercase tracking-wide transition {{ $userSubscribed ? 'bg-white/15 text-white hover:bg-white/20' : 'bg-red-600 text-white hover:bg-red-500' }}">
            {{ $userSubscribed ? 'Subscribed' : 'Subscribe' }}
        </button>
    </div>
</div>
