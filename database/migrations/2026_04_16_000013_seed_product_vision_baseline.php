<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        $performerName = 'Imogen Lucie';
        $performer = DB::table('performers')->where('name', $performerName)->first();
        if (! $performer) {
            $performerId = DB::table('performers')->insertGetId([
                'name' => $performerName,
                'slug' => Str::slug($performerName),
                'bio' => 'Featured performer profile generated as baseline.',
                'avatar_url' => null,
                'birth_date' => null,
                'country' => 'UK',
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        } else {
            $performerId = $performer->id;
        }

        $embeddedVideos = DB::table('embedded_videos')->where('status', 'published')->take(2)->get();
        foreach ($embeddedVideos as $video) {
            $exists = DB::table('performerables')
                ->where('performer_id', $performerId)
                ->where('performerable_type', 'App\\Models\\EmbeddedVideo')
                ->where('performerable_id', $video->id)
                ->exists();

            if (! $exists) {
                DB::table('performerables')->insert([
                    'performer_id' => $performerId,
                    'performerable_type' => 'App\\Models\\EmbeddedVideo',
                    'performerable_id' => $video->id,
                    'role_name' => 'lead',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $group = DB::table('fan_groups')->where('slug', Str::slug($performerName . ' Fans'))->first();
        if (! $group) {
            DB::table('fan_groups')->insert([
                'performer_id' => $performerId,
                'name' => $performerName . ' Fans',
                'slug' => Str::slug($performerName . ' Fans'),
                'description' => 'Official fan club for curated updates and drops.',
                'is_private' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $firstUserId = DB::table('users')->orderBy('id')->value('id');
        foreach ($embeddedVideos as $video) {
            $title = 'Hot moment: ' . Str::limit($video->title, 40, '');
            $exists = DB::table('short_clips')->where('title', $title)->exists();
            if (! $exists) {
                DB::table('short_clips')->insert([
                    'title' => $title,
                    'video_id' => null,
                    'embedded_video_id' => $video->id,
                    'start_seconds' => 15,
                    'end_seconds' => 45,
                    'highlight_score' => 8.5,
                    'status' => 'published',
                    'created_by' => $firstUserId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        $tagNames = ['redhead', 'cosplay', 'outdoor', 'dominant', 'romance'];
        foreach ($tagNames as $name) {
            $slug = Str::slug($name);
            $tagId = DB::table('tags')->where('slug', $slug)->value('id');
            if (! $tagId) {
                $tagId = DB::table('tags')->insertGetId([
                    'name' => $name,
                    'slug' => $slug,
                    'weight' => 3,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            foreach ($embeddedVideos as $video) {
                $exists = DB::table('taggables')
                    ->where('tag_id', $tagId)
                    ->where('taggable_type', 'App\\Models\\EmbeddedVideo')
                    ->where('taggable_id', $video->id)
                    ->exists();

                if (! $exists) {
                    DB::table('taggables')->insert([
                        'tag_id' => $tagId,
                        'taggable_type' => 'App\\Models\\EmbeddedVideo',
                        'taggable_id' => $video->id,
                        'score' => 3,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        DB::table('fan_groups')->where('slug', Str::slug('Imogen Lucie Fans'))->delete();
        DB::table('performers')->where('slug', Str::slug('Imogen Lucie'))->delete();
        DB::table('short_clips')->where('title', 'like', 'Hot moment:%')->delete();
    }
};

