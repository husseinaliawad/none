<?php

namespace App\Jobs;

use App\Models\VideoImportLog;
use App\Services\PartnerApiImportService;
use App\Services\VideoImportService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ProcessVideoImportApiJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public function __construct(
        public int $importLogId,
        public string $endpoint,
        public ?string $token = null,
        public ?int $userId = null,
    ) {
    }

    public function handle(PartnerApiImportService $apiImportService, VideoImportService $importService): void
    {
        $log = VideoImportLog::findOrFail($this->importLogId);

        try {
            $records = $apiImportService->fetch($this->endpoint, $this->token);
            $importService->importRecords(Collection::make($records), $log, $this->userId);
        } catch (\Throwable $exception) {
            $log->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
                'completed_at' => now(),
            ]);

            Log::error('Video API import failed.', [
                'import_log_id' => $this->importLogId,
                'exception' => $exception,
            ]);

            throw $exception;
        }
    }
}
