<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        * { box-sizing: border-box; }
        html, body { overflow-x: hidden; }
        body {
            margin: 0;
            background: #0f0f0f;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
            min-height: 100vh;
        }

        img, video, iframe { max-width: 100%; }

        .app-header {
            position: fixed;
            top: 0;
            width: 100%;
            background: #0f0f0f;
            border-bottom: 1px solid #333;
            z-index: 1000;
        }

        .app-header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 20px;
        }

        .logo {
            display: flex;
            align-items: center;
            font-size: 24px;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            white-space: nowrap;
        }

        .logo span { color: #ff0000; }

        .search-container {
            flex: 1;
            max-width: 640px;
            margin: 0 20px;
        }

        .search-form {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .search-bar {
            width: 100%;
            padding: 12px 20px;
            background: #121212;
            border: 1px solid #444;
            border-radius: 40px;
            color: #fff;
            font-size: 16px;
        }

        .search-bar:focus {
            outline: none;
            border-color: #ff0000;
        }

        .search-btn {
            border: 0;
            border-radius: 22px;
            padding: 10px 16px;
            background: #ff0000;
            color: #fff;
            cursor: pointer;
            font-weight: 600;
        }

        .top-actions {
            display: flex;
            gap: 14px;
            align-items: center;
        }

        .top-actions a,
        .top-actions button {
            color: #fff;
            text-decoration: none;
            background: transparent;
            border: 0;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .auth-btn {
            font-size: 12px;
            border: 1px solid #3a3a3a;
            border-radius: 999px;
            padding: 8px 12px !important;
            line-height: 1;
        }

        .category-wrap {
            border-top: 1px solid #2a2a2a;
            padding: 8px 20px;
        }

        .main-content {
            margin-top: 76px;
            padding: 20px;
            padding-bottom: 96px;
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
            width: 100%;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: #0f0f0f;
            border-top: 1px solid #333;
            display: flex;
            justify-content: space-around;
            padding: 10px 0 6px;
            z-index: 1000;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #aaa;
            font-size: 12px;
            text-decoration: none;
            transition: .25s ease;
        }

        .nav-item i {
            font-size: 24px;
            margin-bottom: 3px;
        }

        .icon {
            width: 1em;
            height: 1em;
            display: inline-block;
            vertical-align: middle;
            fill: currentColor;
        }

        .logo .icon {
            width: 24px;
            height: 24px;
            margin-right: 8px;
        }

        .top-actions .icon {
            width: 22px;
            height: 22px;
        }

        .nav-item .icon {
            width: 24px;
            height: 24px;
            margin-bottom: 3px;
        }

        .nav-item:hover, .nav-item.active { color: #fff; }

        @media (max-width: 900px) {
            .app-header-inner {
                flex-wrap: wrap;
                padding: 10px 12px;
            }

            .search-container {
                order: 3;
                width: 100%;
                margin: 4px 0 0;
                max-width: none;
            }

            .logo { font-size: 21px; }

            .top-actions {
                font-size: 20px;
                gap: 10px;
            }

            .category-wrap { padding: 8px 12px; }

            .main-content {
                margin-top: 122px;
                padding: 14px;
                padding-bottom: 92px;
            }
        }
    </style>

    @livewireStyles
    @stack('custom-css')
</head>
<body>
    <div id="app">
        <header class="app-header">
            <div class="app-header-inner">
                <a href="{{ url('/') }}" class="logo">
                    <svg viewBox="0 0 24 24" class="icon" style="color: #ff0000;" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
                    <span>{{ \Illuminate\Support\Str::limit(config('app.name', 'xnaik'), 12, '') }}</span>&nbsp;Tube
                </a>

                @php
                    $isShortsPage = request()->routeIs('shorts.index');
                    $isGifsPage = request()->routeIs('gifs.index');
                    $searchAction = $isShortsPage
                        ? route('shorts.index')
                        : ($isGifsPage ? route('gifs.index') : route('search'));
                    $searchInputName = ($isShortsPage || $isGifsPage) ? 'q' : 'query';
                    $searchPlaceholder = $isShortsPage
                        ? 'Search shorts...'
                        : ($isGifsPage ? 'Search gifs...' : 'Search videos...');
                    $searchValue = ($isShortsPage || $isGifsPage) ? request('q') : request('query');
                @endphp

                <div class="search-container">
                    <form action="{{ $searchAction }}" method="GET" class="search-form">
                        <input
                            type="text"
                            name="{{ $searchInputName }}"
                            value="{{ $searchValue }}"
                            placeholder="{{ $searchPlaceholder }}"
                            class="search-bar"
                        >
                        <button type="submit" class="search-btn">Search</button>
                    </form>
                </div>

                <div class="top-actions">
                    @auth
                        @if(Auth::user()->channel)
                            <a href="{{ route('video.create', ['channel' => Auth::user()->channel ]) }}" title="Upload">
                                <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M17 10.5V6a2 2 0 0 0-2-2H5A2 2 0 0 0 3 6v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4.5l4 4v-11l-4 4z"/></svg>
                            </a>
                            <a href="{{ route('channel.index', ['channel' => Auth::user()->channel]) }}" title="Channel">
                                <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 5a3.5 3.5 0 1 1-3.5 3.5A3.5 3.5 0 0 1 12 7zm0 13a8 8 0 0 1-5.6-2.3 6.5 6.5 0 0 1 11.2 0A8 8 0 0 1 12 20z"/></svg>
                            </a>
                        @endif

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" title="Logout" class="auth-btn">Logout</button>
                        </form>
                    @else
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="auth-btn">Login</a>
                        @endif
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="auth-btn">Register</a>
                        @endif
                    @endauth
                </div>
            </div>

            @hasSection('category-menu')
                <div class="category-wrap">
                    @yield('category-menu')
                </div>
            @endif
        </header>

        <main class="main-content">
            @yield('content')
        </main>

        <div class="bottom-nav">
            <a class="nav-item {{ request()->routeIs('home') || request()->path() === '/' ? 'active' : '' }}" href="{{ url('/') }}">
                <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M12 3l9 8h-3v10h-5v-6H11v6H6V11H3z"/></svg>
                <span>Tube</span>
            </a>
            <a class="nav-item {{ request()->routeIs('gifs.index') ? 'active' : '' }}" href="{{ route('gifs.index') }}">
                <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M4 5h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2zm2 2v2h3V7H6zm0 4v2h3v-2H6zm0 4v2h3v-2H6zm9-8v2h3V7h-3zm0 4v2h3v-2h-3zm0 4v2h3v-2h-3z"/></svg>
                <span>GIFs</span>
            </a>
            <a class="nav-item {{ request()->routeIs('shorts.index') ? 'active' : '' }}" href="{{ route('shorts.index') }}">
                <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M13 2L4 14h6l-1 8 9-12h-6z"/></svg>
                <span>Shorts</span>
            </a>
            <a class="nav-item {{ request()->routeIs('fan-groups.index') || request()->routeIs('fan-groups.show') ? 'active' : '' }}" href="{{ route('fan-groups.index') }}">
                <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M3 5h18a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2zm0 2l9 6 9-6z"/></svg>
                <span>Groups</span>
            </a>
        </div>
    </div>

    @stack('scripts')
    @livewireScripts
</body>
</html>
