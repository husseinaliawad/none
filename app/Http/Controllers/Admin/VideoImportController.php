<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BulkImportVideosRequest;
use App\Jobs\ProcessVideoImportApiJob;
use App\Jobs\ProcessVideoImportFileJob;
use App\Models\VideoImportLog;
use Illuminate\Http\RedirectResponse;

class VideoImportController extends Controller
{
    public function index()
    {
        $logs = VideoImportLog::query()
            ->withCount('failures')
            ->latest()
            ->paginate(15);

        return view('admin.imports.index', compact('logs'));
    }

    public function store(BulkImportVideosRequest $request): RedirectResponse
    {
        $sourceType = $request->input('source_type');

        if ($sourceType === 'api') {
            $endpoint = (string) $request->input('api_endpoint');
            $token = $request->input('api_token');

            $log = VideoImportLog::create([
                'source_type' => 'api',
                'source_reference' => $endpoint,
                'status' => 'pending',
                'created_by' => $request->user()->id,
                'meta' => [
                    'queued_from' => 'admin_ui',
                ],
            ]);

            ProcessVideoImportApiJob::dispatch($log->id, $endpoint, $token, $request->user()->id);

            return redirect()->route('admin.imports.index')->with('status', 'API import queued successfully.');
        }

        $file = $request->file('import_file');
        $extension = strtolower($file->getClientOriginalExtension());
        $normalizedType = $sourceType === 'json' || $extension === 'json' ? 'json' : 'csv';
        $path = $file->store('imports/videos', 'local');

        $log = VideoImportLog::create([
            'source_type' => $normalizedType,
            'source_reference' => $file->getClientOriginalName(),
            'status' => 'pending',
            'created_by' => $request->user()->id,
            'meta' => [
                'disk' => 'local',
                'path' => $path,
                'queued_from' => 'admin_ui',
            ],
        ]);

        ProcessVideoImportFileJob::dispatch($log->id, 'local', $path, $normalizedType, $request->user()->id);

        return redirect()->route('admin.imports.index')->with('status', 'File import queued successfully.');
    }

    public function show(VideoImportLog $import)
    {
        $import->load(['failures' => fn ($query) => $query->latest()->limit(50)]);

        return view('admin.imports.show', ['log' => $import]);
    }
}
