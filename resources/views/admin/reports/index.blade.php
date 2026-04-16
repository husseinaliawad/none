@extends('admin.layouts.app', ['pageTitle' => 'Reports & Moderation'])

@section('content')
<x-admin.section-header title="Reports & Moderation" subtitle="Review workflow for flagged content and moderation history." />
<x-admin.panel>
    <x-admin.empty-state title="Workflow scaffold ready" description="Integrate flagging signals and moderation notes table to enable full timeline review." />
</x-admin.panel>
@endsection
