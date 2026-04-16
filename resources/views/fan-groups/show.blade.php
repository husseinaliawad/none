@extends('layouts.app')

@section('content')
<section class="space-y-6">
    <div class="rounded-2xl border border-white/10 bg-panel/80 p-4 sm:p-6">
        <h1 class="text-2xl font-bold text-white">{{ $group->name }}</h1>
        <p class="mt-2 text-sm text-muted">{{ $group->description ?: 'No description yet.' }}</p>
        @if($group->performer)
            <p class="mt-2 text-xs text-muted">Focused Performer: {{ $group->performer->name }}</p>
        @endif
        @auth
            <form method="POST" action="{{ route('fan-groups.join', $group) }}" class="mt-4">
                @csrf
                <button class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white">Join Group</button>
            </form>
        @endauth
    </div>

    <div class="rounded-2xl border border-white/10 bg-panel p-4 sm:p-6">
        <h2 class="mb-3 text-lg font-semibold text-white">Members</h2>
        <div class="space-y-2">
            @forelse($group->members as $member)
                <div class="rounded-lg border border-white/10 px-3 py-2 text-sm text-gray-200">
                    {{ $member->name }} <span class="text-xs text-muted">({{ $member->pivot->role }})</span>
                </div>
            @empty
                <p class="text-sm text-muted">No members yet.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection

