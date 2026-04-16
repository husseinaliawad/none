@extends('admin.layouts.app', ['pageTitle' => 'Import Detail'])

@section('content')
<div class="space-y-5">
    <x-admin.section-header :title="'Import #' . $log->id" subtitle="Detailed validation and failure timeline." />

    <div class="grid gap-4 xl:grid-cols-3">
        <x-admin.panel title="Summary" class="xl:col-span-1">
            <div class="space-y-2 text-sm">
                <p><span class="text-admin-muted">Status:</span> <x-admin.badge :value="$log->status" /></p>
                <p><span class="text-admin-muted">Source Type:</span> {{ strtoupper($log->source_type) }}</p>
                <p><span class="text-admin-muted">Reference:</span> {{ $log->source_reference }}</p>
                <p><span class="text-admin-muted">Total:</span> {{ $log->total_records }}</p>
                <p><span class="text-admin-muted">Imported:</span> {{ $log->imported_records }}</p>
                <p><span class="text-admin-muted">Failed:</span> {{ $log->failed_records }}</p>
                <p><span class="text-admin-muted">Started:</span> {{ $log->started_at ?: 'N/A' }}</p>
                <p><span class="text-admin-muted">Completed:</span> {{ $log->completed_at ?: 'N/A' }}</p>
                <p><span class="text-admin-muted">Error:</span> {{ $log->error_message ?: 'None' }}</p>
            </div>
        </x-admin.panel>

        <x-admin.panel title="Validation Failures" class="xl:col-span-2">
            @if($log->failures->count())
                <div class="space-y-3">
                    @foreach($log->failures as $failure)
                        <div class="rounded-xl border border-rose-400/30 bg-rose-500/10 p-3">
                            <p class="text-xs font-semibold uppercase tracking-wide text-rose-200">Row {{ $failure->row_number ?: '-' }}</p>
                            <p class="mt-1 text-sm text-white">{{ $failure->error_message }}</p>
                            <pre class="mt-2 overflow-x-auto rounded-lg border border-rose-300/30 bg-black/30 p-2 text-xs text-rose-100">{{ json_encode($failure->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                        </div>
                    @endforeach
                </div>
            @else
                <x-admin.empty-state title="No validation failures" description="This import completed without row-level errors." />
            @endif
        </x-admin.panel>
    </div>
</div>
@endsection
