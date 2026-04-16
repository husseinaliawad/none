@extends('admin.layouts.app', ['pageTitle' => 'Dashboard'])

@section('content')
<div class="space-y-6 animate-fadeUp">
    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-6">
        <x-admin.stat-card title="Total Users" :value="number_format($kpis['total_users'])" hint="Registered accounts" tone="accent" />
        <x-admin.stat-card title="Total Videos" :value="number_format($kpis['total_videos'])" hint="Native + embedded" tone="success" />
        <x-admin.stat-card title="Published" :value="number_format($kpis['published_videos'])" hint="Currently visible" tone="success" />
        <x-admin.stat-card title="Pending" :value="number_format($kpis['pending_videos'])" hint="Needs moderation" tone="warning" />
        <x-admin.stat-card title="Views Today" :value="number_format($kpis['views_today'])" hint="Estimated from updates" tone="accent" />
        <x-admin.stat-card title="Comments Today" :value="number_format($kpis['comments_today'])" hint="Engagement signal" tone="danger" />
    </div>

    <div class="grid gap-4 xl:grid-cols-3">
        <x-admin.panel class="xl:col-span-2" title="Performance Trends">
            <div class="grid gap-4 lg:grid-cols-2">
                <x-admin.chart-card id="viewsChart" />
                <x-admin.chart-card id="uploadsChart" />
            </div>
            <div class="mt-4">
                <x-admin.chart-card id="usersChart" />
            </div>
        </x-admin.panel>

        <x-admin.panel title="Quick Moderation Actions">
            <div class="space-y-3">
                <a href="{{ route('admin.videos.index', ['status' => 'draft']) }}" class="group flex items-center justify-between rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-3 hover:border-admin-accent/40 hover:bg-admin-accent/10">
                    <span class="text-sm text-white">Draft Embedded Videos</span>
                    <span class="text-sm font-bold text-admin-accent">{{ number_format($moderation['draft_videos']) }}</span>
                </a>
                <a href="{{ route('admin.comments.index', ['moderation' => 'recent']) }}" class="group flex items-center justify-between rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-3 hover:border-admin-warning/40 hover:bg-amber-500/10">
                    <span class="text-sm text-white">Recent Comments</span>
                    <span class="text-sm font-bold text-amber-200">{{ number_format($moderation['recent_comments']) }}</span>
                </a>
                <a href="{{ route('admin.imports.index') }}" class="group flex items-center justify-between rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-3 hover:border-admin-success/40 hover:bg-emerald-500/10">
                    <span class="text-sm text-white">Import Queue Monitor</span>
                    <span class="text-sm font-bold text-emerald-200">Open</span>
                </a>
            </div>

            <div class="mt-4 rounded-xl border border-admin-border bg-black/20 p-3">
                <p class="text-xs uppercase tracking-wide text-admin-muted">Processing Pipeline</p>
                <p class="mt-2 text-sm text-gray-200">Unprocessed native videos: <span class="font-semibold">{{ number_format($moderation['unprocessed_videos']) }}</span></p>
            </div>
        </x-admin.panel>
    </div>

    <div class="grid gap-4 xl:grid-cols-3">
        <x-admin.panel title="Recent Activity">
            <div class="space-y-3">
                @forelse($recentActivity as $activity)
                    <div class="rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5">
                        <p class="text-sm font-semibold text-white">{{ $activity['title'] }}</p>
                        <p class="mt-1 text-xs text-admin-muted">{{ $activity['meta'] }}</p>
                        <p class="mt-1 text-[11px] text-admin-muted">{{ $activity['time']->diffForHumans() }}</p>
                    </div>
                @empty
                    <x-admin.empty-state title="No recent activity" description="Activity feed will appear here." />
                @endforelse
            </div>
        </x-admin.panel>

        <x-admin.panel title="Recent Uploads">
            <div class="space-y-3">
                @forelse($recentUploads as $video)
                    <div class="flex items-start gap-3 rounded-xl border border-admin-border bg-admin-cardSoft p-2.5">
                        <img src="{{ $video->thumbnail }}" alt="{{ $video->title }}" class="h-14 w-24 rounded-lg object-cover">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-white">{{ $video->title }}</p>
                            <p class="text-xs text-admin-muted">{{ number_format($video->views) }} views • {{ $video->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <x-admin.empty-state title="No uploads yet" description="New uploads will be listed here." />
                @endforelse
            </div>
        </x-admin.panel>

        <x-admin.panel title="Top Categories">
            <div class="space-y-2">
                @forelse($topCategories as $item)
                    <div class="flex items-center justify-between rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2.5">
                        <p class="text-sm font-medium text-white">{{ $item->category }}</p>
                        <x-admin.badge value="published">{{ $item->total }} videos</x-admin.badge>
                    </div>
                @empty
                    <x-admin.empty-state title="No category data" description="Imported categories will appear here." />
                @endforelse
            </div>
        </x-admin.panel>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const chartLabels = @json($chart['labels']);
    const viewsData = @json($chart['daily_views']);
    const uploadsData = @json($chart['uploads']);
    const usersData = @json($chart['new_users']);

    const chartOptions = {
        responsive: true,
        plugins: { legend: { labels: { color: '#cfd3ea' } } },
        scales: {
            x: { ticks: { color: '#8f94b3' }, grid: { color: 'rgba(255,255,255,.06)' } },
            y: { ticks: { color: '#8f94b3' }, grid: { color: 'rgba(255,255,255,.06)' } }
        }
    };

    new Chart(document.getElementById('viewsChart'), {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Daily Views',
                data: viewsData,
                borderColor: '#4f7cff',
                backgroundColor: 'rgba(79,124,255,.2)',
                fill: true,
                tension: .35,
            }]
        },
        options: chartOptions
    });

    new Chart(document.getElementById('uploadsChart'), {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Uploads',
                data: uploadsData,
                backgroundColor: 'rgba(31,201,139,.55)',
                borderRadius: 8,
            }]
        },
        options: chartOptions
    });

    new Chart(document.getElementById('usersChart'), {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'New Users',
                data: usersData,
                borderColor: '#f0b429',
                backgroundColor: 'rgba(240,180,41,.15)',
                fill: true,
                tension: .35,
            }]
        },
        options: chartOptions
    });
</script>
@endpush
