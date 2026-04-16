@foreach ($comments as $comment)
    <article class="rounded-xl border border-white/10 bg-black/20 p-3" x-data="{ open: false, openReply: false }">
        <div class="flex items-start gap-3">
            <img class="h-10 w-10 rounded-full object-cover" src="{{ $comment->user->channel->picture }}" alt="{{ $comment->user->name }}" />

            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-2">
                    <h5 class="text-sm font-semibold text-white">{{ $comment->user->name }}</h5>
                    <small class="text-xs text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                </div>

                <p class="mt-2 text-sm text-gray-200">{{ $comment->body }}</p>

                <div class="mt-3 flex items-center gap-3 text-xs">
                    <a href="" class="text-gray-400 hover:text-white" @click.prevent="openReply = !openReply">Reply</a>

                    @if ($comment->replies->count())
                        <a href="" class="text-gray-400 hover:text-white" @click.prevent="open = !open">
                            {{ $comment->replies->count() }} replies
                        </a>
                    @endif
                </div>

                @auth
                    <div class="mt-3" x-show="openReply">
                        <livewire:comment.new-comment :video="$video" :col="$comment->id" :key="$comment->id . uniqid()" />
                    </div>
                @endauth

                @if ($comment->replies->count())
                    <div class="mt-3 space-y-3 pl-3 sm:pl-6" x-show="open">
                        @include('includes.recursive', ['comments' => $comment->replies])
                    </div>
                @endif
            </div>
        </div>
    </article>
@endforeach
