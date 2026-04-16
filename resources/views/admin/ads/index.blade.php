@extends('admin.layouts.app', ['pageTitle' => 'Ads & Banners'])

@section('content')
<x-admin.section-header title="Ads & Banner Management" subtitle="Manage placements and activation state with visual preview blocks." />
<x-admin.panel>
    <div class="grid gap-3 md:grid-cols-2">
        <div class="rounded-xl border border-admin-border bg-admin-cardSoft p-4">
            <p class="text-sm font-semibold text-white">Homepage Hero Banner</p>
            <p class="mt-1 text-xs text-admin-muted">Placement: Top of homepage</p>
            <x-admin.badge value="inactive" class="mt-3" />
        </div>
        <div class="rounded-xl border border-admin-border bg-admin-cardSoft p-4">
            <p class="text-sm font-semibold text-white">Sidebar Ad Slot</p>
            <p class="mt-1 text-xs text-admin-muted">Placement: Watch page sidebar</p>
            <x-admin.badge value="inactive" class="mt-3" />
        </div>
    </div>
</x-admin.panel>
@endsection
