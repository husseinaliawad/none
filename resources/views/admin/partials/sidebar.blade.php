<aside class="fixed inset-y-0 left-0 z-50 w-72 -translate-x-full border-r border-admin-border bg-admin-card/95 p-5 shadow-lift backdrop-blur transition duration-200 lg:translate-x-0" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
    <div class="mb-8 flex items-center justify-between">
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center rounded-xl bg-white/5 p-1.5 hover:bg-white/10">
            <img
                src="{{ asset('logo.jpg') }}"
                alt="{{ config('app.name', 'Tube') }} logo"
                class="h-9 w-auto rounded-md object-contain"
            >
        </a>
        <button class="rounded-lg p-2 text-admin-muted hover:bg-white/5 lg:hidden" @click="sidebarOpen = false">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <nav class="scrollbar-thin h-[calc(100vh-8rem)] space-y-1 overflow-y-auto pr-1">
        @php
            $groups = [
                'Overview' => [
                    ['route' => 'admin.dashboard', 'label' => 'Dashboard'],
                    ['route' => 'admin.analytics.index', 'label' => 'Analytics'],
                    ['route' => 'admin.reports.index', 'label' => 'Reports & Moderation'],
                ],
                'Content' => [
                    ['route' => 'admin.videos.index', 'label' => 'Videos'],
                    ['route' => 'admin.performers.index', 'label' => 'Performers'],
                    ['route' => 'admin.categories.index', 'label' => 'Categories'],
                    ['route' => 'admin.tags.index', 'label' => 'Tags'],
                    ['route' => 'admin.comments.index', 'label' => 'Comments'],
                    ['route' => 'admin.imports.index', 'label' => 'Imports'],
                ],
                'Management' => [
                    ['route' => 'admin.users.index', 'label' => 'Users'],
                    ['route' => 'admin.roles.index', 'label' => 'Roles & Permissions'],
                    ['route' => 'admin.ads.index', 'label' => 'Ads & Banners'],
                    ['route' => 'admin.settings.index', 'label' => 'Settings'],
                ],
            ];
        @endphp

        @foreach($groups as $groupTitle => $links)
            <div class="pt-3">
                <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-[0.14em] text-admin-muted">{{ $groupTitle }}</p>
                <div class="space-y-1">
                    @foreach($links as $link)
                        @php $active = request()->routeIs($link['route']) || request()->routeIs($link['route'] . '.*'); @endphp
                        <a href="{{ route($link['route']) }}" class="group flex items-center justify-between rounded-xl px-3 py-2.5 text-sm transition {{ $active ? 'bg-admin-accent/20 text-white' : 'text-admin-muted hover:bg-white/5 hover:text-white' }}">
                            <span class="font-medium">{{ $link['label'] }}</span>
                            <span class="h-1.5 w-1.5 rounded-full {{ $active ? 'bg-admin-accent' : 'bg-transparent group-hover:bg-admin-muted' }}"></span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>
</aside>

<div class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden" x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false"></div>
