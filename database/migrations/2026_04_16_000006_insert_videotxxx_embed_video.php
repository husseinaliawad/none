<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $embedUrl = 'https://videotxxx.com/embed/21252293/?promo=23760&source=';

        $exists = DB::table('embedded_videos')
            ->where('embed_url', $embedUrl)
            ->exists();

        if ($exists) {
            return;
        }

        $userId = DB::table('users')->orderBy('id')->value('id');

        if (! $userId) {
            return;
        }

        $baseSlug = Str::slug('videotxxx-21252293');
        $slug = $baseSlug;
        $counter = 1;

        while (DB::table('embedded_videos')->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $now = now();

        DB::table('embedded_videos')->insert([
            'title' => 'videotxxx 21252293',
            'slug' => $slug,
            'description' => null,
            'thumbnail_url' => null,
            'embed_url' => $embedUrl,
            'source_name' => 'Videotxxx',
            'source_video_id' => '21252293',
            'category' => null,
            'tags' => json_encode([]),
            'status' => 'published',
            'published_at' => $now,
            'created_by' => $userId,
            'updated_by' => $userId,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    public function down(): void
    {
        DB::table('embedded_videos')
            ->where('embed_url', 'https://videotxxx.com/embed/21252293/?promo=23760&source=')
            ->delete();
    }
};

