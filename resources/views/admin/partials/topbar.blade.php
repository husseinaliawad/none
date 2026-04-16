<header class="sticky top-0 z-30 border-b border-admin-border/70 bg-admin-base/70 px-4 py-3 backdrop-blur sm:px-6 lg:px-8">
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <button class="rounded-xl border border-admin-border bg-admin-card p-2.5 text-admin-muted hover:text-white lg:hidden" @click="sidebarOpen = true">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>

            <div>
                <p class="text-xs uppercase tracking-[0.2em] text-admin-muted">Admin Workspace</p>
                <h1 class="text-base font-semibold text-white sm:text-lg">{{ $pageTitle ?? 'Dashboard' }}</h1>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <form action="{{ route('admin.videos.index') }}" method="GET" class="hidden md:block">
                <div class="flex items-center rounded-xl border border-admin-border bg-admin-cardSoft px-3 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-admin-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z"/></svg>
                    <input name="search" placeholder="Search videos, users..." class="ml-2 w-56 bg-transparent text-sm text-white placeholder:text-admin-muted focus:outline-none">
                </div>
            </form>

            <button class="relative rounded-xl border border-admin-border bg-admin-card p-2.5 text-admin-muted hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                @if($notificationsCount > 0)
                    <span class="absolute -right-1 -top-1 inline-flex min-h-[18px] min-w-[18px] items-center justify-center rounded-full bg-admin-danger px-1 text-[10px] font-bold text-white">{{ $notificationsCount }}</span>
                @endif
            </button>

            <div class="flex items-center gap-2 rounded-xl border border-admin-border bg-admin-card px-2 py-1.5">
                <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-admin-accent to-cyan-400"></div>
                <div class="hidden sm:block">
                    <p class="text-xs text-admin-muted">Signed in as</p>
                    <p class="text-sm font-semibold text-white">{{ auth()->user()->name ?? 'Admin' }}</p>
                </div>
            </div>
        </div>
    </div>
</header>
