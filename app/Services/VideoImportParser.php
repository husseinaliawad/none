<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class VideoImportParser
{
    public function parseFromDisk(string $disk, string $path, string $type): Collection
    {
        if (! Storage::disk($disk)->exists($path)) {
            throw new InvalidArgumentException('Import file does not exist.');
        }

        $content = Storage::disk($disk)->get($path);

        return $this->parseRawContent($content, $type);
    }

    public function parseRawContent(string $content, string $type): Collection
    {
        return match ($type) {
            'csv' => $this->parseCsv($content),
            'json' => $this->parseJson($content),
            default => throw new InvalidArgumentException('Unsupported import type.'),
        };
    }

    protected function parseCsv(string $content): Collection
    {
        $rows = preg_split('/\r\n|\r|\n/', trim($content));

        if (empty($rows) || count($rows) === 1 && trim($rows[0]) === '') {
            return collect();
        }

        $headers = str_getcsv(array_shift($rows));
        $headers = array_map(fn ($header) => trim((string) $header), $headers);

        return collect($rows)
            ->filter(fn ($row) => trim((string) $row) !== '')
            ->values()
            ->map(function (string $row) use ($headers) {
                $values = str_getcsv($row);
                $item = [];

                foreach ($headers as $index => $header) {
                    $item[$header] = Arr::get($values, $index);
                }

                return $item;
            });
    }

    protected function parseJson(string $content): Collection
    {
        $decoded = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('Invalid JSON import file.');
        }

        if (Arr::isAssoc($decoded) && isset($decoded['data']) && is_array($decoded['data'])) {
            $decoded = $decoded['data'];
        }

        if (! is_array($decoded)) {
            throw new InvalidArgumentException('JSON payload must be an array of video objects.');
        }

        return collect($decoded)->values();
    }
}
