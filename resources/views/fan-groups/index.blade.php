@extends('layouts.app')

@section('content')
<section class="space-y-6">
    <div class="rounded-2xl border border-white/10 bg-panel/80 p-4 sm:p-6">
        <h1 class="text-2xl font-bold text-white">Fan Groups</h1>
        <p class="mt-2 text-sm text-muted">Join communities around your favorite performers.</p>
    </div>

    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($groups as $group)
            <a href="{{ route('fan-groups.show', $group) }}" class="rounded-xl border border-white/10 bg-panel p-4 hover:bg-white/5">
                <p class="text-sm font-semibold text-white">{{ $group->name }}</p>
                <p class="mt-1 text-xs text-muted">{{ $group->performer?->name ?: 'General' }}</p>
                <p class="mt-2 text-xs text-muted">{{ $group->is_private ? 'Private group' : 'Public group' }}</p>
            </a>
        @empty
            <p class="rounded-xl border border-white/10 bg-panel p-4 text-sm text-muted">No groups found.</p>
        @endforelse
    </div>

    <div>{{ $groups->links() }}</div>
</section>
@endsection

