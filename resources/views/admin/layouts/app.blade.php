<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ ($pageTitle ?? 'Admin') . ' • ' . config('app.name', 'Laravel') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Manrope', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        admin: {
                            base: '#09090f',
                            card: '#11111a',
                            cardSoft: '#171724',
                            border: '#26263a',
                            muted: '#8f94b3',
                            accent: '#4f7cff',
                            success: '#1fc98b',
                            warning: '#f0b429',
                            danger: '#ff5b7f',
                        }
                    },
                    boxShadow: {
                        soft: '0 15px 35px rgba(0,0,0,.35)',
                        lift: '0 25px 45px rgba(0,0,0,.45)',
                    },
                    keyframes: {
                        fadeUp: {
                            '0%': { opacity: '0', transform: 'translateY(6px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    },
                    animation: {
                        fadeUp: 'fadeUp .35s ease-out',
                    }
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            background: radial-gradient(circle at top right, #1a2042 0%, #09090f 45%, #06060a 100%);
            color: #e6e9f5;
        }
        .scrollbar-thin::-webkit-scrollbar { width: 8px; height: 8px; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: #303050; border-radius: 9999px; }
    </style>

    @stack('head')
</head>
<body class="min-h-screen antialiased">
@php
    $notificationsCount = ($notificationsCount ?? 0)
        ?: \App\Models\EmbeddedVideo::where('status', 'draft')->count() + \App\Models\Comment::whereDate('created_at', '>=', now()->subDay())->count();
@endphp
<div class="flex min-h-screen" x-data="{ sidebarOpen: false }">
    @include('admin.partials.sidebar')

    <div class="flex min-h-screen flex-1 flex-col lg:pl-72">
        @include('admin.partials.topbar', ['notificationsCount' => $notificationsCount])

        <main class="flex-1 p-4 sm:p-6 lg:p-8">
            @if(session('status'))
                <div class="mb-4 animate-fadeUp rounded-2xl border border-emerald-400/35 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 rounded-2xl border border-red-400/35 bg-red-500/10 px-4 py-3 text-sm text-red-200">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
