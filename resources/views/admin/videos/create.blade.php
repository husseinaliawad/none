@extends('admin.layouts.app', ['pageTitle' => 'Create Video'])

@section('content')
<div class="space-y-5">
    <x-admin.section-header title="Create Embedded Video" subtitle="Add a new video from approved providers only.">
        <x-slot name="actions">
            <a href="{{ route('admin.videos.index') }}" class="rounded-xl border border-admin-border bg-admin-cardSoft px-4 py-2 text-sm text-white hover:bg-white/10">Back to Library</a>
        </x-slot>
    </x-admin.section-header>

    <form method="POST" action="{{ route('admin.videos.store') }}" class="space-y-4">
        @csrf
        <x-admin.panel>
            @include('admin.videos._form')
        </x-admin.panel>
        <div class="flex justify-end">
            <button class="rounded-xl bg-admin-accent px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-500">Save Video</button>
        </div>
    </form>
</div>
@endsection
