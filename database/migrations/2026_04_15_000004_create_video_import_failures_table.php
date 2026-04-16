<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('video_import_failures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_import_log_id')->constrained('video_import_logs')->cascadeOnDelete();
            $table->unsignedInteger('row_number')->nullable();
            $table->json('payload')->nullable();
            $table->text('error_message');
            $table->timestamps();

            $table->index('row_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('video_import_failures');
    }
};
