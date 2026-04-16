<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rank_levels', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('level')->unique();
            $table->string('name', 120);
            $table->unsignedInteger('min_points')->default(0)->index();
            $table->json('perks')->nullable();
            $table->timestamps();
        });

        Schema::create('user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('points')->default(0)->index();
            $table->unsignedInteger('current_level')->default(1)->index();
            $table->unsignedBigInteger('total_watch_seconds')->default(0);
            $table->unsignedInteger('streak_days')->default(0);
            $table->timestamp('last_watched_at')->nullable();
            $table->timestamps();
        });

        Schema::create('fan_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('performer_id')->nullable()->constrained('performers')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_private')->default(false)->index();
            $table->timestamps();
        });

        Schema::create('fan_group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fan_group_id')->constrained('fan_groups')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('role', ['owner', 'moderator', 'member'])->default('member')->index();
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();

            $table->unique(['fan_group_id', 'user_id']);
        });

        Schema::create('short_clips', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('video_id')->nullable()->constrained('videos')->nullOnDelete();
            $table->foreignId('embedded_video_id')->nullable()->constrained('embedded_videos')->nullOnDelete();
            $table->unsignedInteger('start_seconds');
            $table->unsignedInteger('end_seconds');
            $table->decimal('highlight_score', 5, 2)->default(0);
            $table->enum('status', ['draft', 'published'])->default('draft')->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        $now = now();

        DB::table('rank_levels')->insert([
            [
                'level' => 1,
                'name' => 'Rookie',
                'min_points' => 0,
                'perks' => json_encode(['base_feed' => true]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'level' => 2,
                'name' => 'Insider',
                'min_points' => 1200,
                'perks' => json_encode(['early_access' => true, 'fan_groups' => true]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'level' => 3,
                'name' => 'Elite',
                'min_points' => 5000,
                'perks' => json_encode(['premium_drops' => true, 'exclusive_shortlists' => true]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('fan_groups')->insert([
            [
                'performer_id' => null,
                'name' => 'General Hot Picks',
                'slug' => Str::slug('General Hot Picks'),
                'description' => 'Launch community for curated trending content.',
                'is_private' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('short_clips');
        Schema::dropIfExists('fan_group_members');
        Schema::dropIfExists('fan_groups');
        Schema::dropIfExists('user_progress');
        Schema::dropIfExists('rank_levels');
    }
};

