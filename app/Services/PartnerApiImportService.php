<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use InvalidArgumentException;

class PartnerApiImportService
{
    public function fetch(string $endpoint, ?string $token = null): array
    {
        if (! filter_var($endpoint, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('API endpoint must be a valid URL.');
        }

        $host = strtolower((string) parse_url($endpoint, PHP_URL_HOST));

        if (! $this->isAllowedHost($host)) {
            throw new InvalidArgumentException('API host is not approved.');
        }

        $request = Http::timeout(30)->acceptJson();

        if ($token) {
            $request = $request->withToken($token);
        }

        $response = $request->get($endpoint);

        if (! $response->successful()) {
            throw new InvalidArgumentException('Partner API request failed with status ' . $response->status() . '.');
        }

        $payload = $response->json();

        if (isset($payload['data']) && is_array($payload['data'])) {
            return $payload['data'];
        }

        if (! is_array($payload)) {
            throw new InvalidArgumentException('Partner API response is not a valid array payload.');
        }

        return Arr::isAssoc($payload) ? [$payload] : $payload;
    }

    protected function isAllowedHost(string $host): bool
    {
        $allowed = config('video_sources.allowed_api_hosts', []);

        foreach ($allowed as $allowedHost) {
            $allowedHost = strtolower(trim($allowedHost));

            if ($host === $allowedHost || Str::endsWith($host, '.' . $allowedHost)) {
                return true;
            }
        }

        return false;
    }
}
