@extends('admin.layouts.app', ['pageTitle' => 'Performers'])

@section('content')
<div class="space-y-5">
    <x-admin.section-header title="Performer Profiles" subtitle="Manage actor profiles and link them to videos.">
        <x-slot name="actions">
            <a href="{{ route('admin.performers.create') }}" class="rounded-xl bg-admin-accent px-4 py-2 text-sm font-semibold text-white hover:bg-blue-500">Add Performer</a>
        </x-slot>
    </x-admin.section-header>

    <x-admin.panel>
        <form method="GET" class="mb-4 flex gap-2">
            <input name="search" value="{{ request('search') }}" placeholder="Search performer..." class="w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2 text-sm text-white">
            <button class="rounded-xl bg-white/10 px-4 py-2 text-sm text-white">Search</button>
        </form>

        <div class="space-y-2">
            @forelse($performers as $performer)
                <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-3">
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-white">{{ $performer->name }}</p>
                        <p class="mt-1 text-xs text-admin-muted">{{ $performer->country ?: 'N/A' }} • {{ $performer->is_active ? 'Active' : 'Inactive' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('performers.show', $performer) }}" class="rounded-lg border border-admin-border px-3 py-1.5 text-xs text-white">View</a>
                        <a href="{{ route('admin.performers.edit', $performer) }}" class="rounded-lg border border-admin-border px-3 py-1.5 text-xs text-white">Edit</a>
                        <form method="POST" action="{{ route('admin.performers.destroy', $performer) }}" onsubmit="return confirm('Delete performer?')">
                            @csrf
                            @method('DELETE')
                            <button class="rounded-lg border border-rose-400/40 px-3 py-1.5 text-xs text-rose-300">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <x-admin.empty-state title="No performers" description="Create your first performer profile." />
            @endforelse
        </div>
    </x-admin.panel>

    <div>{{ $performers->links() }}</div>
</div>
@endsection

