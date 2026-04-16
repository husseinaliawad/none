<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->string('storyboard_vtt_url')->nullable()->after('processed_file');
            $table->string('storyboard_sprite_url')->nullable()->after('storyboard_vtt_url');
            $table->json('preview_timeline')->nullable()->after('storyboard_sprite_url');
        });

        Schema::table('embedded_videos', function (Blueprint $table) {
            $table->string('storyboard_vtt_url')->nullable()->after('embed_url');
            $table->string('storyboard_sprite_url')->nullable()->after('storyboard_vtt_url');
            $table->json('preview_timeline')->nullable()->after('storyboard_sprite_url');
        });
    }

    public function down(): void
    {
        Schema::table('embedded_videos', function (Blueprint $table) {
            $table->dropColumn(['storyboard_vtt_url', 'storyboard_sprite_url', 'preview_timeline']);
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn(['storyboard_vtt_url', 'storyboard_sprite_url', 'preview_timeline']);
        });
    }
};

