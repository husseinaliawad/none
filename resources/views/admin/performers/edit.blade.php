@extends('admin.layouts.app', ['pageTitle' => 'Edit Performer'])

@section('content')
<div class="space-y-5">
    <x-admin.section-header title="Edit Performer" subtitle="Update performer profile details.">
        <x-slot name="actions">
            <a href="{{ route('admin.performers.index') }}" class="rounded-xl border border-admin-border bg-admin-cardSoft px-4 py-2 text-sm text-white">Back</a>
        </x-slot>
    </x-admin.section-header>

    <form method="POST" action="{{ route('admin.performers.update', $performer) }}" class="space-y-4">
        @csrf
        @method('PUT')
        <x-admin.panel>
            @include('admin.performers._form')
        </x-admin.panel>
        <div class="flex justify-end">
            <button class="rounded-xl bg-admin-accent px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-500">Update Performer</button>
        </div>
    </form>
</div>
@endsection

