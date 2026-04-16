@extends('admin.layouts.app', ['pageTitle' => 'Comments Moderation'])

@section('content')
<div class="space-y-5">
    <x-admin.section-header title="Comments Moderation" subtitle="Review discussions and remove problematic content quickly." />

    <x-admin.panel>
        <form method="GET" class="grid gap-3 md:grid-cols-4">
            <input name="search" value="{{ request('search') }}" placeholder="Search comments" class="rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2 text-sm text-white md:col-span-2">
            <select name="moderation" class="rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2 text-sm text-white">
                <option value="">All</option>
                <option value="recent" @selected(request('moderation') === 'recent')>Recent 48h</option>
            </select>
            <button class="rounded-xl bg-white/10 px-3 py-2 text-sm font-semibold text-white hover:bg-white/20">Filter</button>
        </form>
    </x-admin.panel>

    <x-admin.panel>
        @if($comments->count())
            <div class="space-y-3">
                @foreach($comments as $comment)
                    <div class="rounded-xl border border-admin-border bg-admin-cardSoft p-3">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-white">{{ optional($comment->user)->name ?: 'Deleted User' }}</p>
                                <p class="text-xs text-admin-muted">On: {{ optional($comment->video)->title ?: 'Deleted Video' }} • {{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                            <x-admin.badge value="pending">In Review</x-admin.badge>
                        </div>
                        <p class="mt-2 text-sm text-gray-200">{{ $comment->body }}</p>
                        <div class="mt-3 flex justify-end">
                            <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}" onsubmit="return confirm('Delete this comment?');">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-lg border border-rose-400/30 bg-rose-500/10 px-3 py-1.5 text-xs font-semibold text-rose-200 hover:bg-rose-500/20">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">{{ $comments->links() }}</div>
        @else
            <x-admin.empty-state title="No comments to moderate" description="Moderation queue is currently clear." />
        @endif
    </x-admin.panel>
</div>
@endsection
