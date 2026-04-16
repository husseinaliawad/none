<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkImportVideosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'source_type' => ['required', Rule::in(['csv', 'json', 'api'])],
            'import_file' => ['nullable', 'file', 'max:10240', 'mimes:csv,txt,json'],
            'api_endpoint' => ['nullable', 'url', 'max:2048'],
            'api_token' => ['nullable', 'string', 'max:1024'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $type = $this->input('source_type');

            if (in_array($type, ['csv', 'json'], true) && ! $this->hasFile('import_file')) {
                $validator->errors()->add('import_file', 'Import file is required for CSV/JSON imports.');
            }

            if ($type === 'api' && blank($this->input('api_endpoint'))) {
                $validator->errors()->add('api_endpoint', 'API endpoint is required for API imports.');
            }
        });
    }
}
