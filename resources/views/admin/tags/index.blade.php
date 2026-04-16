@extends('admin.layouts.app', ['pageTitle' => 'Tags'])

@section('content')
<div class="space-y-5">
    <x-admin.section-header title="Tag Management" subtitle="Chip-based tag visualization and quick distribution view." />
    <x-admin.panel>
        @if($tagCounts->count())
            <div class="flex flex-wrap gap-2">
                @foreach($tagCounts as $tag => $count)
                    <span class="inline-flex items-center gap-2 rounded-full border border-admin-border bg-admin-cardSoft px-3 py-1.5 text-xs text-white">
                        #{{ $tag }}
                        <span class="rounded-full bg-admin-accent/20 px-2 py-0.5 text-[10px] text-admin-accent">{{ $count }}</span>
                    </span>
                @endforeach
            </div>
        @else
            <x-admin.empty-state title="No tags found" description="Add comma-separated tags in video editor." />
        @endif
    </x-admin.panel>
</div>
@endsection
