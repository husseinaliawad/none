<?php

namespace App\Services;

use App\Models\EmbeddedVideo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class EmbeddedThumbnailResolver
{
    public function resolve(EmbeddedVideo $video): ?string
    {
        if ($this->isUsableUrl($video->thumbnail_url)) {
            return $video->thumbnail_url;
        }

        $firstFrame = collect($video->preview_timeline ?? [])->first();
        if ($this->isUsableUrl($firstFrame)) {
            return $firstFrame;
        }

        $providerThumb = $this->providerThumbnail($video);
        if ($this->isUsableUrl($providerThumb)) {
            $this->persistThumbnail($video, $providerThumb);
            return $providerThumb;
        }

        $cacheKey = 'embedded-thumb:' . sha1((string) $video->embed_url);

        $remoteThumb = Cache::remember($cacheKey, now()->addHours(12), function () use ($video) {
            return $this->extractThumbnailFromEmbedPage((string) $video->embed_url);
        });

        if ($this->isUsableUrl($remoteThumb)) {
            $this->persistThumbnail($video, $remoteThumb);
            return $remoteThumb;
        }

        return null;
    }

    protected function providerThumbnail(EmbeddedVideo $video): ?string
    {
        $source = Str::lower((string) $video->source_name);
        $id = trim((string) $video->source_video_id);
        $host = Str::lower((string) parse_url((string) $video->embed_url, PHP_URL_HOST));

        if ($id !== '' && Str::contains($source, 'youtube')) {
            return 'https://i.ytimg.com/vi/' . $id . '/hqdefault.jpg';
        }

        if ($id !== '' && Str::contains($source, 'vimeo')) {
            return 'https://vumbnail.com/' . $id . '.jpg';
        }

        if ($id !== '' && ctype_digit($id) && (Str::contains($source, 'videotxxx') || Str::contains($source, 'txxx') || Str::contains($host, 'txxx'))) {
            $numericId = (int) $id;
            $bucket = (int) (floor($numericId / 1000) * 1000);

            return sprintf('https://tn.txxx.tube/contents/videos_screenshots/%d/%d/preview.jpg', $bucket, $numericId);
        }

        return null;
    }

    protected function extractThumbnailFromEmbedPage(string $embedUrl): ?string
    {
        if (! filter_var($embedUrl, FILTER_VALIDATE_URL)) {
            return null;
        }

        try {
            $response = Http::connectTimeout(3)
                ->timeout(6)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0 Safari/537.36',
                    'Accept-Language' => 'en-US,en;q=0.9',
                ])
                ->get($embedUrl);
        } catch (\Throwable $e) {
            report($e);
            return null;
        }

        if (! $response->successful()) {
            return null;
        }

        $html = $response->body();

        $candidates = [
            '/<meta[^>]+property=["\']og:image["\'][^>]+content=["\']([^"\']+)["\']/i',
            '/<meta[^>]+name=["\']twitter:image["\'][^>]+content=["\']([^"\']+)["\']/i',
            '/<meta[^>]+itemprop=["\']image["\'][^>]+content=["\']([^"\']+)["\']/i',
            '/data-src=["\']([^"\']+\.(?:jpg|jpeg|png|webp)(?:\?[^"\']*)?)["\']/i',
            '/https?:\/\/[^"\'\s>]+\.(?:jpg|jpeg|png|webp)(?:\?[^"\'\s>]*)?/i',
        ];

        foreach ($candidates as $pattern) {
            if (preg_match($pattern, $html, $matches)) {
                $url = $matches[1] ?? $matches[0] ?? null;

                if ($this->isUsableUrl($url)) {
                    return $url;
                }
            }
        }

        return null;
    }

    protected function persistThumbnail(EmbeddedVideo $video, string $thumbnailUrl): void
    {
        if ($this->isUsableUrl($video->thumbnail_url)) {
            return;
        }

        try {
            $video->forceFill(['thumbnail_url' => $thumbnailUrl])->save();
        } catch (\Throwable $e) {
            report($e);
        }
    }

    protected function isUsableUrl(?string $url): bool
    {
        return is_string($url) && filter_var($url, FILTER_VALIDATE_URL);
    }
}
