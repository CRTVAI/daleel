<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRoadmap extends Model
{
    protected $fillable = [
        'user_id', 'title', 'goal', 'tools', 'focus', 'level', 'curriculum', 'progress', 'is_auto_generated', 'metadata'
    ];

    protected $casts = [
        'tools' => 'array',
        'curriculum' => 'array',
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getProgressAttribute()
    {
        $phases = $this->curriculum['phases'] ?? $this->curriculum;
        if (!is_array($phases)) return 0;

        $allVideoIds = collect($phases)
            ->filter(fn($p) => is_array($p))
            ->pluck('videos')->flatten(1)->pluck('id')->filter()->unique()->values();

        if ($allVideoIds->isEmpty()) return 0;

        $progressMap = \App\Models\UserVideoProgress::where('user_id', $this->user_id)
            ->whereIn('content_id', $allVideoIds)
            ->get()->keyBy('content_id');

        $contents = \App\Models\Content::whereIn('id', $allVideoIds)->get()->keyBy('id');

        $totalDuration = 0;
        $totalWatched = 0;

        foreach ($allVideoIds as $id) {
            $content = $contents->get($id);
            $p = $progressMap->get($id);
            $duration = max((int) ($content?->resolved_duration_seconds ?? 0), (int) ($p?->duration_seconds ?? 0));
            $watched = min((int) ($p?->watched_seconds ?? 0), $duration > 0 ? $duration : (int) ($p?->watched_seconds ?? 0));
            $completion = $duration > 0 ? min(100, round(($watched / $duration) * 100, 2)) : (float) ($p?->completion_percent ?? 0);
            $isCompleted = ($p?->completed ?? false) || $completion >= 90;
            if ($isCompleted && $duration > 0) {
                $watched = $duration;
            }
            $totalDuration += $duration;
            $totalWatched += $watched;
        }

        return $totalDuration > 0 ? min(100, round(($totalWatched / $totalDuration) * 100)) : 0;
    }
}
