@extends('admin.layouts.app', ['pageTitle' => 'Edit Video'])

@section('content')
<div class="space-y-5">
    <x-admin.section-header title="Edit Video" subtitle="Update metadata, source, and publishing settings.">
        <x-slot name="actions">
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.videos.show', $video) }}" class="rounded-xl border border-admin-border bg-admin-cardSoft px-4 py-2 text-sm text-white hover:bg-white/10">View Details</a>
                <a href="{{ route('admin.videos.index') }}" class="rounded-xl border border-admin-border bg-admin-cardSoft px-4 py-2 text-sm text-white hover:bg-white/10">Back</a>
            </div>
        </x-slot>
    </x-admin.section-header>

    <form method="POST" action="{{ route('admin.videos.update', $video) }}" class="space-y-4">
        @csrf
        @method('PUT')
        <x-admin.panel>
            @include('admin.videos._form')
        </x-admin.panel>
        <div class="flex justify-end">
            <button class="rounded-xl bg-admin-accent px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-500">Update Video</button>
        </div>
    </form>
</div>
@endsection
