<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $embedUrl = 'https://pornhoarder.net/player_t.php?video=N0lhaDUxOWNZaGdGRlFaaVk5dFZRQ0toOGdaRWVUSXVndnMrQTZPR3Npdz0=';

        $alreadyExists = DB::table('embedded_videos')
            ->where('embed_url', $embedUrl)
            ->exists();

        if ($alreadyExists) {
            return;
        }

        $userId = DB::table('users')->orderBy('id')->value('id');

        if (! $userId) {
            $userId = DB::table('users')->insertGetId([
                'name' => 'System',
                'email' => 'system+' . Str::random(8) . '@local.test',
                'email_verified_at' => now(),
                'password' => Hash::make(Str::random(32)),
                'role' => 'admin',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $baseSlug = Str::slug('pornhoarder-player');
        $slug = $baseSlug;
        $counter = 1;

        while (DB::table('embedded_videos')->where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        $now = now();

        DB::table('embedded_videos')->insert([
            'title' => 'pornhoarder player',
            'slug' => $slug,
            'description' => null,
            'thumbnail_url' => null,
            'embed_url' => $embedUrl,
            'source_name' => 'Pornhoarder',
            'source_video_id' => 'N0lhaDUxOWNZaGdGRlFaaVk5dFZRQ0toOGdaRWVUSXVndnMrQTZPR3Npdz0=',
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
            ->where('embed_url', 'https://pornhoarder.net/player_t.php?video=N0lhaDUxOWNZaGdGRlFaaVk5dFZRQ0toOGdaRWVUSXVndnMrQTZPR3Npdz0=')
            ->delete();
    }
};

