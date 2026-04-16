<?php

namespace App\Http\Requests\Admin;

use App\Services\EmbedSourceValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmbeddedVideoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('embedded_videos', 'slug')],
            'description' => ['nullable', 'string'],
            'thumbnail_url' => ['nullable', 'url', 'max:2048'],
            'embed_url' => [
                'required',
                'string',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    try {
                        app(EmbedSourceValidator::class)->normalizeAndValidate((string) $value);
                    } catch (\Throwable $exception) {
                        $fail($exception->getMessage());
                    }
                },
            ],
            'source_name' => ['nullable', 'string', 'max:120'],
            'source_video_id' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:120'],
            'tags' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'published_at' => ['nullable', 'date'],
        ];
    }
}
