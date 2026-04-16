@extends('admin.layouts.app', ['pageTitle' => 'Create Performer'])

@section('content')
<div class="space-y-5">
    <x-admin.section-header title="Create Performer" subtitle="Add a new performer profile.">
        <x-slot name="actions">
            <a href="{{ route('admin.performers.index') }}" class="rounded-xl border border-admin-border bg-admin-cardSoft px-4 py-2 text-sm text-white">Back</a>
        </x-slot>
    </x-admin.section-header>

    <form method="POST" action="{{ route('admin.performers.store') }}" class="space-y-4">
        @csrf
        <x-admin.panel>
            @include('admin.performers._form')
        </x-admin.panel>
        <div class="flex justify-end">
            <button class="rounded-xl bg-admin-accent px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-500">Save Performer</button>
        </div>
    </form>
</div>
@endsection

