@extends('admin.layouts.app', ['pageTitle' => 'Analytics'])

@section('content')
<div class="space-y-5">
    <x-admin.section-header title="Analytics" subtitle="Traffic, top performers, and watch trends." />

    <div class="grid gap-4 xl:grid-cols-3">
        <x-admin.panel class="xl:col-span-2" title="Traffic Overview">
            <x-admin.chart-card id="trafficChart" />
        </x-admin.panel>

        <x-admin.panel title="Top Categories">
            <div class="space-y-2">
                @forelse($topCategories as $category)
                    <div class="flex items-center justify-between rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2">
                        <span class="text-sm text-white">{{ $category->category }}</span>
                        <x-admin.badge value="published">{{ $category->total }}</x-admin.badge>
                    </div>
                @empty
                    <x-admin.empty-state title="No category insights" description="Add categorized videos to unlock this panel." />
                @endforelse
            </div>
        </x-admin.panel>
    </div>

    <x-admin.panel title="Top Videos">
        @if($topVideos->count())
            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
                @foreach($topVideos as $video)
                    <div class="rounded-xl border border-admin-border bg-admin-cardSoft p-3">
                        <p class="truncate text-sm font-semibold text-white">{{ $video->title }}</p>
                        <p class="mt-1 text-xs text-admin-muted">{{ number_format($video->views) }} views</p>
                        <p class="text-xs text-admin-muted">{{ $video->created_at->diffForHumans() }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <x-admin.empty-state title="No video analytics yet" description="Views will appear after users start watching." />
        @endif
    </x-admin.panel>
</div>
@endsection

@push('scripts')
<script>
new Chart(document.getElementById('trafficChart'), {
    type: 'line',
    data: {
        labels: @json($traffic['labels']),
        datasets: [
            {
                label: 'Uploads',
                data: @json($traffic['uploads']),
                borderColor: '#4f7cff',
                backgroundColor: 'rgba(79,124,255,.2)',
                tension: 0.35,
                fill: true,
            },
            {
                label: 'New Users',
                data: @json($traffic['users']),
                borderColor: '#1fc98b',
                backgroundColor: 'rgba(31,201,139,.15)',
                tension: 0.35,
                fill: true,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { labels: { color: '#cfd3ea' } } },
        scales: {
            x: { ticks: { color: '#8f94b3' }, grid: { color: 'rgba(255,255,255,.06)' } },
            y: { ticks: { color: '#8f94b3' }, grid: { color: 'rgba(255,255,255,.06)' } }
        }
    }
});
</script>
@endpush
