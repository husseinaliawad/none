<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Tube') }}</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Arial, sans-serif; }
    body { background: #0f0f0f; color: #fff; }

    header {
      position: fixed;
      top: 0;
      width: 100%;
      background: #0f0f0f;
      border-bottom: 1px solid #333;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 20px;
      gap: 12px;
      z-index: 1000;
    }

    .logo {
      display: flex;
      align-items: center;
      font-size: 24px;
      font-weight: bold;
      color: #fff;
      text-decoration: none;
      white-space: nowrap;
    }

    .logo span { color: #ff0000; }

    .search-container {
      flex: 1;
      max-width: 600px;
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
      gap: 18px;
      align-items: center;
    }

    .top-actions a {
      color: #fff;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    main {
      margin-top: 76px;
      padding: 20px;
      padding-bottom: 96px;
    }

    h2 { margin-bottom: 25px; color: #fff; }

    .video-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(310px, 1fr));
      gap: 25px;
    }

    .video-card {
      background: #1a1a1a;
      border-radius: 12px;
      overflow: hidden;
      transition: transform 0.3s ease;
      text-decoration: none;
      color: inherit;
      display: block;
    }

    .video-card:hover {
      transform: translateY(-6px);
    }

    .thumbnail {
      position: relative;
      width: 100%;
      height: 175px;
      overflow: hidden;
      background: #000;
    }

    .thumbnail img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .duration {
      position: absolute;
      bottom: 8px;
      right: 8px;
      background: rgba(0,0,0,0.85);
      padding: 3px 8px;
      border-radius: 4px;
      font-size: 13px;
      font-weight: 600;
    }

    .video-info {
      padding: 12px;
    }

    .video-title {
      font-size: 15.5px;
      line-height: 1.35;
      margin-bottom: 8px;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .video-meta {
      color: #aaa;
      font-size: 12px;
      line-height: 1.4;
    }

    .storyboard {
      display: flex;
      gap: 5px;
      padding: 0 12px 14px;
      overflow-x: auto;
      scrollbar-width: none;
    }

    .storyboard::-webkit-scrollbar {
      display: none;
    }

    .storyboard img {
      width: 68px;
      height: 46px;
      object-fit: cover;
      border-radius: 4px;
      border: 1px solid #444;
      flex-shrink: 0;
      background: #0f0f0f;
    }

    .empty-state {
      background: #1a1a1a;
      border: 1px solid #333;
      border-radius: 12px;
      padding: 20px;
      color: #aaa;
      text-align: center;
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
      cursor: pointer;
      transition: all 0.3s;
      text-decoration: none;
    }

    .nav-item:hover, .nav-item.active {
      color: #fff;
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
      width: 23px;
      height: 23px;
    }

    .nav-item .icon {
      width: 26px;
      height: 26px;
      margin-bottom: 3px;
    }

    @media (max-width: 900px) {
      header {
        flex-wrap: wrap;
        padding: 10px 12px;
      }

      .search-container {
        order: 3;
        width: 100%;
        margin: 4px 0 0;
        max-width: none;
      }

      .top-actions {
        font-size: 20px;
        gap: 12px;
      }

      .logo {
        font-size: 21px;
      }

      main {
        margin-top: 122px;
        padding: 14px;
        padding-bottom: 92px;
      }

      .video-grid {
        grid-template-columns: 1fr;
        gap: 16px;
      }
    }
  </style>
</head>
<body>
  @php
      $feedCards = collect();

      foreach (($forYouFeed ?? collect()) as $row) {
          $item = $row['item'] ?? null;
          if (!$item) {
              continue;
          }

          $isEmbedded = ($row['type'] ?? null) === 'embedded';
          $title = $item->title ?? 'Untitled Video';
          $url = $isEmbedded ? route('embed.watch', $item) : route('video.watch', $item);
          $thumbnail = $isEmbedded
              ? ($item->resolved_thumbnail_url ?: 'https://placehold.co/640x360/111827/9ca3af?text=Video+Preview')
              : ($item->thumbnail_image ? asset('videos/' . $item->uid . '/' . $item->thumbnail_image) : 'https://placehold.co/640x360/111827/9ca3af?text=No+Thumbnail');

          $frames = collect($item->preview_timeline ?? [])->filter()->take(4)->values();
          if ($frames->isEmpty()) {
              $frames = collect([$thumbnail, $thumbnail, $thumbnail, $thumbnail]);
          }

          $metaName = $isEmbedded ? ($item->source_name ?: 'Imported Source') : (optional($item->channel)->name ?: 'Unknown Channel');
          $views = number_format((int) ($item->views ?? 0));
          $uploaded = optional($item->created_at)->diffForHumans() ?: 'Recently';

          $feedCards->push([
              'url' => $url,
              'title' => $title,
              'thumbnail' => $thumbnail,
              'duration' => $item->duration ?: '00:00',
              'meta' => $metaName . ' • ' . $views . ' views • ' . $uploaded,
              'frames' => $frames,
          ]);
      }

      if ($feedCards->isEmpty()) {
          foreach (($latestVideos ?? collect())->take(18) as $video) {
              $thumbnail = $video->thumbnail_image
                  ? asset('videos/' . $video->uid . '/' . $video->thumbnail_image)
                  : 'https://placehold.co/640x360/111827/9ca3af?text=No+Thumbnail';

              $frames = collect($video->preview_timeline ?? [])->filter()->take(4)->values();
              if ($frames->isEmpty()) {
                  $frames = collect([$thumbnail, $thumbnail, $thumbnail, $thumbnail]);
              }

              $feedCards->push([
                  'url' => route('video.watch', $video),
                  'title' => $video->title,
                  'thumbnail' => $thumbnail,
                  'duration' => $video->duration ?: '00:00',
                  'meta' => (optional($video->channel)->name ?: 'Unknown Channel') . ' • ' . number_format((int) ($video->views ?? 0)) . ' views • ' . (optional($video->created_at)->diffForHumans() ?: 'Recently'),
                  'frames' => $frames,
              ]);
          }
      }
  @endphp

  <header>
    <a href="{{ url('/') }}" class="logo">
      <svg viewBox="0 0 24 24" class="icon" style="color: #ff0000;" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
      <span>{{ \Illuminate\Support\Str::limit(config('app.name', 'xnaik'), 12, '') }}</span>&nbsp;Tube
    </a>

    <div class="search-container">
      <form action="{{ route('search') }}" method="GET" class="search-form">
        <input
          type="text"
          name="query"
          value="{{ request('query') }}"
          class="search-bar"
          placeholder="Search videos..."
        >
        <button type="submit" class="search-btn">Search</button>
      </form>
    </div>

    <div class="top-actions">
      @auth
        @if(auth()->user()->channel)
          <a href="{{ route('video.create', ['channel' => auth()->user()->channel]) }}" title="Upload">
            <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M17 10.5V6a2 2 0 0 0-2-2H5A2 2 0 0 0 3 6v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-4.5l4 4v-11l-4 4z"/></svg>
          </a>
        @endif
        <a href="{{ route('fan-groups.index') }}" title="Groups">
          <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M12 22a2.5 2.5 0 0 0 2.45-2h-4.9A2.5 2.5 0 0 0 12 22zm7-6V11a7 7 0 1 0-14 0v5L3 18v1h18v-1z"/></svg>
        </a>
        <form action="{{ route('logout') }}" method="POST" style="display:inline-flex;">
          @csrf
          <button type="submit" title="Logout" style="border:0;background:none;color:#fff;cursor:pointer;">
            <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 5a3.5 3.5 0 1 1-3.5 3.5A3.5 3.5 0 0 1 12 7zm0 13a8 8 0 0 1-5.6-2.3 6.5 6.5 0 0 1 11.2 0A8 8 0 0 1 12 20z"/></svg>
          </button>
        </form>
      @else
        <a href="{{ route('login') }}" title="Login">
          <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 5a3.5 3.5 0 1 1-3.5 3.5A3.5 3.5 0 0 1 12 7zm0 13a8 8 0 0 1-5.6-2.3 6.5 6.5 0 0 1 11.2 0A8 8 0 0 1 12 20z"/></svg>
        </a>
      @endauth
    </div>
  </header>

  <main>
    <h2>Popular Videos</h2>

    <div class="video-grid">
      @forelse($feedCards as $card)
        <a href="{{ $card['url'] }}" class="video-card">
          <div class="thumbnail">
            <img src="{{ $card['thumbnail'] }}" alt="{{ $card['title'] }}" loading="lazy" onerror="this.onerror=null;this.src='https://placehold.co/640x360/111827/9ca3af?text=No+Thumbnail';">
            <div class="duration">{{ $card['duration'] }}</div>
          </div>
          <div class="video-info">
            <div class="video-title">{{ $card['title'] }}</div>
            <small class="video-meta">{{ $card['meta'] }}</small>
          </div>
          <div class="storyboard">
            @foreach($card['frames'] as $frame)
              <img src="{{ $frame }}" alt="Storyboard frame" loading="lazy" onerror="this.onerror=null;this.src='{{ $card['thumbnail'] }}';">
            @endforeach
          </div>
        </a>
      @empty
        <div class="empty-state">No videos available yet.</div>
      @endforelse
    </div>
  </main>

  <div class="bottom-nav">
    <a class="nav-item active" href="{{ url('/') }}">
      <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M12 3l9 8h-3v10h-5v-6H11v6H6V11H3z"/></svg>
      <span>Tube</span>
    </a>
    <a class="nav-item" href="{{ route('gifs.index') }}">
      <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M4 5h16a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2zm2 2v2h3V7H6zm0 4v2h3v-2H6zm0 4v2h3v-2H6zm9-8v2h3V7h-3zm0 4v2h3v-2h-3zm0 4v2h3v-2h-3z"/></svg>
      <span>GIFs</span>
    </a>
    <a class="nav-item" href="{{ route('shorts.index') }}">
      <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M13 2L4 14h6l-1 8 9-12h-6z"/></svg>
      <span>Shorts</span>
    </a>
    <a class="nav-item" href="{{ route('fan-groups.index') }}">
      <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M3 5h18a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2zm0 2l9 6 9-6z"/></svg>
      <span>Groups</span>
    </a>
  </div>
</body>
</html>
