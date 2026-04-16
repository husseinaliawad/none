@extends('admin.layouts.app', ['pageTitle' => 'Roles & Permissions'])

@section('content')
<x-admin.section-header title="Roles & Permissions" subtitle="Manage role groups and capability mapping across modules." />
<x-admin.panel>
    <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
        <div class="rounded-xl border border-admin-border bg-admin-cardSoft p-4">
            <p class="text-sm font-semibold text-white">Admin</p>
            <p class="mt-1 text-xs text-admin-muted">Full platform control, moderation, and settings.</p>
        </div>
        <div class="rounded-xl border border-admin-border bg-admin-cardSoft p-4">
            <p class="text-sm font-semibold text-white">Editor</p>
            <p class="mt-1 text-xs text-admin-muted">Manage videos, comments, and imports.</p>
        </div>
        <div class="rounded-xl border border-admin-border bg-admin-cardSoft p-4">
            <p class="text-sm font-semibold text-white">Moderator</p>
            <p class="mt-1 text-xs text-admin-muted">Review comments and flagged content only.</p>
        </div>
    </div>
</x-admin.panel>
@endsection
