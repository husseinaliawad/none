<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('bio')->nullable();
            $table->string('avatar_url')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('country', 120)->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('performerables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('performer_id')->constrained('performers')->cascadeOnDelete();
            $table->morphs('performerable');
            $table->string('role_name', 120)->nullable();
            $table->timestamps();

            $table->unique(['performer_id', 'performerable_type', 'performerable_id'], 'performerables_unique_link');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performerables');
        Schema::dropIfExists('performers');
    }
};

