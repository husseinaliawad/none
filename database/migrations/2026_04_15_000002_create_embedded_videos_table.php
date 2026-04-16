<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('embedded_videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->string('embed_url', 2048);
            $table->string('source_name', 120);
            $table->string('source_video_id', 255)->nullable();
            $table->string('category', 120)->nullable()->index();
            $table->json('tags')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('source_name');
            $table->index(['source_name', 'source_video_id']);
            $table->unique('embed_url');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('embedded_videos');
    }
};
