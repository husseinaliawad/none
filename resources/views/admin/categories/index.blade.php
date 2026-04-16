@extends('admin.layouts.app', ['pageTitle' => 'Categories'])

@section('content')
<div class="space-y-5">
    <x-admin.section-header title="Category Management" subtitle="Visual overview of category distribution with room for nested taxonomy." />
    <x-admin.panel>
        @if($categories->count())
            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                @foreach($categories as $item)
                    <div class="rounded-xl border border-admin-border bg-admin-cardSoft p-4">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-semibold text-white">{{ $item->category }}</p>
                            <span class="h-3 w-3 rounded-full bg-gradient-to-br from-admin-accent to-cyan-400"></span>
                        </div>
                        <p class="mt-2 text-xs text-admin-muted">{{ $item->total }} videos</p>
                    </div>
                @endforeach
            </div>
        @else
            <x-admin.empty-state title="No categories available" description="Assign categories to videos from the video editor." />
        @endif
    </x-admin.panel>
</div>
@endsection
