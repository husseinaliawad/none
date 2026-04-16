<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        surface: '#0f0f13',
                        panel: '#17171d',
                        muted: '#9ca3af',
                    },
                    boxShadow: {
                        glow: '0 12px 30px rgba(0,0,0,.45)',
                    },
                },
            },
        };
    </script>

    <style>
        body {
            background: radial-gradient(circle at top, #1a1a24 0%, #0b0b0f 55%);
            color: #e5e7eb;
            min-height: 100vh;
        }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    @livewireStyles
    @stack('custom-css')
</head>
<body>
    <div id="app" class="min-h-screen">
        <header class="sticky top-0 z-50 border-b border-white/10 bg-black/85 backdrop-blur">
            <div class="mx-auto flex max-w-[1500px] items-center gap-4 px-3 py-3 sm:px-5">
                <a href="{{ url('/') }}" class="shrink-0 rounded-lg bg-white/5 p-1.5 hover:bg-white/10">
                    <img
                        src="{{ asset('logo.jpg') }}"
                        alt="{{ config('app.name', 'Tube') }} logo"
                        class="h-9 w-auto rounded-md object-contain"
                    >
                </a>

                <form action="{{ route('search') }}" method="GET" class="mx-auto w-full max-w-2xl">
                    <div class="flex items-center rounded-xl border border-white/10 bg-panel">
                        <input
                            type="text"
                            name="query"
                            value="{{ request('query') }}"
                            placeholder="Search videos..."
                            class="w-full bg-transparent px-4 py-2.5 text-sm text-white placeholder:text-muted focus:outline-none"
                        >
                        <button type="submit" class="rounded-r-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-500">
                            Search
                        </button>
                    </div>
                </form>

                <nav class="flex shrink-0 items-center gap-2 text-sm">
                    @guest
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="rounded-lg border border-white/15 px-3 py-2 hover:bg-white/5">Login</a>
                        @endif
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="rounded-lg bg-white/10 px-3 py-2 hover:bg-white/20">Register</a>
                        @endif
                    @else
                        @if(Auth::user()->channel)
                            <a href="{{ route('video.create', ['channel' => Auth::user()->channel ]) }}" class="hidden rounded-lg border border-white/15 px-3 py-2 hover:bg-white/5 sm:inline-flex">Upload</a>
                            <a href="{{ route('channel.index', ['channel' => Auth::user()->channel]) }}" class="rounded-lg border border-white/15 px-3 py-2 hover:bg-white/5">{{ Auth::user()->name }}</a>
                        @else
                            <span class="rounded-lg border border-white/15 px-3 py-2">{{ Auth::user()->name }}</span>
                        @endif
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="rounded-lg bg-red-600 px-3 py-2 font-medium hover:bg-red-500">Logout</button>
                        </form>
                    @endguest
                </nav>
            </div>

            @hasSection('category-menu')
                <div class="border-t border-white/10 bg-black/60">
                    <div class="mx-auto max-w-[1500px] px-3 py-2 sm:px-5">
                        @yield('category-menu')
                    </div>
                </div>
            @endif
        </header>

        <main class="mx-auto max-w-[1500px] px-3 py-5 sm:px-5 sm:py-6">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
    @livewireScripts
</body>
</html>
