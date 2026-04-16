<div class="space-y-6">
    @push('custom-css')
        <link href="https://vjs.zencdn.net/7.20.3/video-js.css" rel="stylesheet" />
    @endpush

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <div class="lg:col-span-8 xl:col-span-9">
            <div class="overflow-hidden rounded-2xl border border-white/10 bg-black" wire:ignore>
                <video
                    id="yt-video"
                    class="video-js vjs-big-play-centered h-auto w-full"
                    controls
                    preload="auto"
                    poster="{{ $video->thumbnail_image ? asset('videos/'. $video->uid . '/' . $video->thumbnail_image) : 'https://placehold.co/1280x720/111827/9ca3af?text=No+Thumbnail' }}"
                    data-setup="{}"
                >
                    <source src="{{ asset('videos/'. $video->uid . '/' . $video->processed_file )}}" type="application/x-mpegURL" />
                </video>
            </div>

            <div class="mt-4 rounded-2xl border border-white/10 bg-panel p-4 sm:p-5">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <h1 class="text-xl font-bold text-white sm:text-2xl">{{ $video->title }}</h1>
                        <p class="mt-2 text-sm text-muted">{{ number_format($video->views) }} views • {{ $video->uploaded_date }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <livewire:video.voting :video="$video" />
                    </div>
                </div>

                <div class="mt-5 border-t border-white/10 pt-4">
                    <livewire:channel.channel-info :channel="$video->channel" />
                </div>
            </div>

            <div class="rounded-2xl border border-white/10 bg-panel p-4 sm:p-5">
                <h2 class="mb-4 text-lg font-semibold text-white">Comments ({{ $video->AllCommentsCount() }})</h2>
                @auth
                    <div class="mb-4">
                        <livewire:comment.new-comment :video="$video" :col=0 :key="$video->id" />
                    </div>
                @endauth
                <livewire:comment.all-comments :video="$video" />
            </div>
        </div>

        <aside class="lg:col-span-4 xl:col-span-3">
            <div class="rounded-2xl border border-white/10 bg-panel p-3">
                <h3 class="mb-3 px-1 text-sm font-semibold uppercase tracking-wide text-gray-300">Related Videos</h3>
                <div class="space-y-3">
                    @forelse($relatedVideos as $related)
                        <a href="{{ route('video.watch', $related) }}" class="group flex gap-3 rounded-xl p-2 transition hover:bg-white/5">
                            <div class="relative w-36 shrink-0 overflow-hidden rounded-lg">
                                <img
                                    src="{{ $related->thumbnail_image ? asset('videos/' . $related->uid . '/' . $related->thumbnail_image) : 'https://placehold.co/320x180/111827/9ca3af?text=No+Thumbnail' }}"
                                    alt="{{ $related->title }}"
                                    class="aspect-video w-full object-cover"
                                >
                                <span class="absolute bottom-1 right-1 rounded bg-black/80 px-1.5 py-0.5 text-[10px] font-semibold text-white">{{ $related->duration ?: '00:00' }}</span>
                            </div>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-white" title="{{ $related->title }}">{{ $related->title }}</p>
                                <p class="mt-1 text-xs text-muted">{{ number_format($related->views) }} views</p>
                                <p class="mt-0.5 text-xs text-muted">{{ $related->created_at?->diffForHumans() }}</p>
                            </div>
                        </a>
                    @empty
                        <p class="rounded-lg border border-white/10 p-3 text-sm text-muted">No related videos found.</p>
                    @endforelse
                </div>
            </div>
        </aside>
    </div>

    @push('scripts')
        <script src="https://vjs.zencdn.net/7.20.3/video.min.js"></script>
        <script>
            var player = videojs('yt-video');
            player.on('timeupdate', function() {
                if (this.currentTime() > 3) {
                    this.off('timeupdate');
                    Livewire.emit('VideoViewed');
                }
            });
        </script>
    @endpush
</div>
