<?php

namespace App\Jobs;

use App\Models\VideoImportLog;
use App\Services\VideoImportParser;
use App\Services\VideoImportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessVideoImportFileJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public function __construct(
        public int $importLogId,
        public string $disk,
        public string $path,
        public string $type,
        public ?int $userId = null,
    ) {
    }

    public function handle(VideoImportParser $parser, VideoImportService $importService): void
    {
        $log = VideoImportLog::findOrFail($this->importLogId);

        try {
            $records = $parser->parseFromDisk($this->disk, $this->path, $this->type);
            $importService->importRecords($records, $log, $this->userId);
        } catch (\Throwable $exception) {
            $log->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
                'completed_at' => now(),
            ]);

            Log::error('Video file import failed.', [
                'import_log_id' => $this->importLogId,
                'exception' => $exception,
            ]);

            throw $exception;
        }
    }
}
