<?php

namespace App\Services;

use App\Models\RankLevel;
use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Support\Carbon;

class UserProgressService
{
    public function trackWatchSession(User $user, int $watchSeconds = 60): UserProgress
    {
        $progress = $user->progress()->firstOrCreate(
            ['user_id' => $user->id],
            [
                'points' => 0,
                'current_level' => 1,
                'total_watch_seconds' => 0,
                'streak_days' => 0,
                'last_watched_at' => null,
            ]
        );

        $pointsToAdd = max((int) floor($watchSeconds / 30), 1);
        $now = now();

        $last = $progress->last_watched_at ? Carbon::parse($progress->last_watched_at) : null;
        $streak = $progress->streak_days;
        if (! $last || ! $last->isToday()) {
            $streak = $last && $last->isYesterday() ? ($streak + 1) : 1;
        }

        $newPoints = (int) $progress->points + $pointsToAdd;
        $level = (int) RankLevel::query()
            ->where('min_points', '<=', $newPoints)
            ->max('level');

        $progress->update([
            'points' => $newPoints,
            'current_level' => max($level, 1),
            'total_watch_seconds' => (int) $progress->total_watch_seconds + $watchSeconds,
            'streak_days' => $streak,
            'last_watched_at' => $now,
        ]);

        return $progress->refresh();
    }
}

