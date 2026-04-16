<div>
    <div class="flex items-center gap-4 text-sm text-gray-300">
        <button type="button" class="group inline-flex items-center gap-2 rounded-lg border border-white/10 px-3 py-2 hover:bg-white/5" wire:click.prevent="like">
            <span class="material-icons text-xl {{ $likeActive ? 'text-blue-400' : 'text-gray-400 group-hover:text-white' }}">thumb_up</span>
            <span>{{ $likes }}</span>
        </button>

        <button type="button" class="group inline-flex items-center gap-2 rounded-lg border border-white/10 px-3 py-2 hover:bg-white/5" wire:click.prevent="dislike">
            <span class="material-icons text-xl {{ $dislikeActive ? 'text-blue-400' : 'text-gray-400 group-hover:text-white' }}">thumb_down</span>
            <span>{{ $dislikes }}</span>
        </button>
    </div>
</div>
