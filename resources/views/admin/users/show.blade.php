@extends('admin.layouts.app', ['pageTitle' => 'User Profile'])

@section('content')
<div class="space-y-5">
    <x-admin.section-header :title="$user->name" subtitle="User profile, role, and uploads.">
        <x-slot name="actions">
            <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-admin-border bg-admin-cardSoft px-4 py-2 text-sm text-white hover:bg-white/10">Back</a>
        </x-slot>
    </x-admin.section-header>

    <div class="grid gap-4 lg:grid-cols-3">
        <x-admin.panel title="Profile Overview">
            <div class="space-y-3 text-sm">
                <div><p class="text-admin-muted">Email</p><p class="text-white">{{ $user->email }}</p></div>
                <div><p class="text-admin-muted">Role</p><x-admin.badge :value="$user->role ?: 'user'">{{ $user->role ?: 'user' }}</x-admin.badge></div>
                <div><p class="text-admin-muted">Status</p><x-admin.badge :value="$user->email_verified_at ? 'active' : 'pending'">{{ $user->email_verified_at ? 'Verified' : 'Unverified' }}</x-admin.badge></div>
                <div><p class="text-admin-muted">Joined</p><p class="text-white">{{ $user->created_at }}</p></div>
                <div><p class="text-admin-muted">Uploaded Videos</p><p class="text-white">{{ $videoCount }}</p></div>
            </div>
        </x-admin.panel>

        <x-admin.panel class="lg:col-span-2" title="Recent Uploads">
            @if($uploadedVideos->count())
                <div class="space-y-2">
                    @foreach($uploadedVideos as $video)
                        <div class="rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5">
                            <p class="truncate text-sm font-semibold text-white">{{ $video->title }}</p>
                            <p class="text-xs text-admin-muted">{{ number_format($video->views) }} views • {{ $video->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <x-admin.empty-state title="No uploads" description="This user has not uploaded videos yet." />
            @endif
        </x-admin.panel>
    </div>
</div>
@endsection
