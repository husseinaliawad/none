<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;

class EmbedSourceValidator
{
    public function normalizeAndValidate(string $input): array
    {
        $embedUrl = $this->extractEmbedUrl($input);

        if (! filter_var($embedUrl, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Embed URL must be a valid URL.');
        }

        $parts = parse_url($embedUrl);
        $scheme = strtolower((string) Arr::get($parts, 'scheme'));

        if (! in_array($scheme, ['http', 'https'], true)) {
            throw new InvalidArgumentException('Only HTTP/HTTPS embed URLs are supported.');
        }

        $host = strtolower((string) Arr::get($parts, 'host'));

        if ($host === '' || ! $this->isAllowedDomain($host)) {
            throw new InvalidArgumentException('Embed source domain is not in the approved whitelist.');
        }

        $cleanUrl = $this->rebuildCleanUrl($parts);

        return [
            'embed_url' => $cleanUrl,
            'source_name' => $this->detectSourceName($host),
            'source_video_id' => $this->detectSourceVideoId($parts),
            'domain' => $host,
        ];
    }

    protected function extractEmbedUrl(string $input): string
    {
        $trimmed = trim($input);

        if (Str::contains(Str::lower($trimmed), '<iframe')) {
            if (! preg_match('/<iframe[^>]*\ssrc=["\']([^"\']+)["\']/i', $trimmed, $matches)) {
                throw new InvalidArgumentException('Malformed iframe code: src attribute is missing or invalid.');
            }

            $trimmed = trim($matches[1]);
        }

        if (Str::contains(Str::lower($trimmed), '<script') || Str::contains(Str::lower($trimmed), '<object')) {
            throw new InvalidArgumentException('Unsupported embed markup. Use a direct embed URL.');
        }

        return $trimmed;
    }

    protected function rebuildCleanUrl(array $parts): string
    {
        $scheme = $parts['scheme'] ?? 'https';
        $host = $parts['host'] ?? '';
        $port = isset($parts['port']) ? ':' . $parts['port'] : '';
        $path = $parts['path'] ?? '';
        $query = isset($parts['query']) ? '?' . $parts['query'] : '';

        return sprintf('%s://%s%s%s%s', $scheme, $host, $port, $path, $query);
    }

    public function isAllowedDomain(string $host): bool
    {
        $allowedDomains = config('video_sources.allowed_domains', []);

        foreach ($allowedDomains as $allowedDomain) {
            $allowedDomain = strtolower(trim($allowedDomain));

            if ($host === $allowedDomain || Str::endsWith($host, '.' . $allowedDomain)) {
                return true;
            }
        }

        return false;
    }

    protected function detectSourceName(string $host): string
    {
        $sourceMap = config('video_sources.source_map', []);

        foreach ($sourceMap as $domain => $label) {
            if ($host === $domain || Str::endsWith($host, '.' . $domain)) {
                return $label;
            }
        }

        return Str::headline($host);
    }

    protected function detectSourceVideoId(array $parts): ?string
    {
        parse_str($parts['query'] ?? '', $query);

        foreach (['source_video_id', 'video_id', 'video', 'id', 'v'] as $key) {
            if (! empty($query[$key])) {
                return (string) $query[$key];
            }
        }

        $path = trim((string) ($parts['path'] ?? ''), '/');

        if ($path === '') {
            return null;
        }

        $segments = explode('/', $path);

        return (string) end($segments);
    }
}
