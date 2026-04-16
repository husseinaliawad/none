@extends('admin.layouts.app', ['pageTitle' => 'Import System'])

@section('content')
<div class="space-y-5">
    <x-admin.section-header title="Import Center" subtitle="Queue CSV/JSON/API imports with clean validation and auditing." />

    <div class="grid gap-4 xl:grid-cols-2">
        <x-admin.panel title="Upload CSV / JSON">
            <form method="POST" action="{{ route('admin.imports.store') }}" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-admin-muted">File Type</label>
                    <select name="source_type" class="w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2 text-sm text-white">
                        <option value="csv">CSV</option>
                        <option value="json">JSON</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-admin-muted">Import File</label>
                    <input type="file" name="import_file" accept=".csv,.json,.txt" class="w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2 text-sm text-white">
                </div>
                <button class="w-full rounded-xl bg-admin-accent px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-500">Queue File Import</button>
            </form>
        </x-admin.panel>

        <x-admin.panel title="Pull From Approved API">
            <form method="POST" action="{{ route('admin.imports.store') }}" class="space-y-3">
                @csrf
                <input type="hidden" name="source_type" value="api">
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-admin-muted">API Endpoint</label>
                    <input type="url" name="api_endpoint" placeholder="https://api.partner1.com/videos" class="w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2 text-sm text-white">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-admin-muted">API Token</label>
                    <input type="text" name="api_token" class="w-full rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2 text-sm text-white">
                </div>
                <button class="w-full rounded-xl bg-white/10 px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/20">Queue API Import</button>
            </form>
        </x-admin.panel>
    </div>

    <x-admin.panel title="Import History">
        @if($logs->count())
            <div class="space-y-3">
                @foreach($logs as $log)
                    <a href="{{ route('admin.imports.show', $log) }}" class="block rounded-xl border border-admin-border bg-admin-cardSoft p-3 hover:border-admin-accent/35">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <div>
                                <p class="text-sm font-semibold text-white">#{{ $log->id }} • {{ strtoupper($log->source_type) }} • {{ $log->source_reference }}</p>
                                <p class="text-xs text-admin-muted">Imported {{ $log->imported_records }}/{{ $log->total_records }} • Failed {{ $log->failed_records }}</p>
                            </div>
                            <x-admin.badge :value="$log->status" />
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-4">{{ $logs->links() }}</div>
        @else
            <x-admin.empty-state title="No imports yet" description="Run your first import to populate this timeline." />
        @endif
    </x-admin.panel>
</div>
@endsection
