<div class="space-y-3">
    <div class="flex items-center gap-3">
        <img src="{{ auth()->user()->channel->picture }}" class="h-10 w-10 rounded-full object-cover" alt="{{ auth()->user()->name }}">
        <input
            type="text"
            wire:model="body"
            class="w-full rounded-lg border border-white/15 bg-black/30 px-3 py-2 text-sm text-white placeholder:text-gray-500 focus:border-white/40 focus:outline-none"
            placeholder="Add a public comment..."
        >
    </div>

    @if($body)
        <div class="flex justify-end gap-2">
            <a href="" wire:click.prevent="resetForm" class="rounded-lg border border-white/15 px-3 py-1.5 text-sm text-gray-300 hover:bg-white/5">Cancel</a>
            <a href="" wire:click.prevent="addComment" class="rounded-lg bg-red-600 px-3 py-1.5 text-sm font-semibold text-white hover:bg-red-500">Comment</a>
        </div>
    @endif
</div>
