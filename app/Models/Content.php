<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'type',
        'video_url',
        'video_url_ar',
        'youtube_id',
        'language',
        'duration_seconds',
        'thumbnail',
        'description',
        'tags',
        'category',
        'skill_level',
        'status',
        'is_featured',
        'course_id',
        'section_part_label',
        'sort_order',
        'connected_tools',
        'category_id',
        'reference_url',
        'video_duration',
        'srt_file_en',
        'srt_file_ar',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'connected_tools' => 'array',
    ];

    // ── Accessors ──────────────────────────────────────────────

    /**
     * Extract YouTube video ID from any YouTube URL format.
     */
    public function getYoutubeIdAttribute(): ?string
    {
        return $this->extractYoutubeId($this->video_url);
    }

    public function getYoutubeIdArAttribute(): ?string
    {
        return $this->extractYoutubeId($this->video_url_ar);
    }

    public function extractYoutubeId($url): ?string
    {
        if (!$url) return null;
        preg_match(
            '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
            $url,
            $match
        );
        return $match[1] ?? null;
    }

    /**
     * Return the best available thumbnail URL.
     */
    public function getThumbnailUrlAttribute(): string
    {
        // 1. If explicit thumbnail is set, use it
        if ($this->thumbnail && !str_contains($this->thumbnail, 'broken')) return $this->thumbnail;

        // 2. If it's a YouTube video, get the HQ thumbnail
        if ($this->youtube_id) {
            // hqdefault is more reliable than maxresdefault which may not exist for all videos
            return "https://img.youtube.com/vi/{$this->youtube_id}/hqdefault.jpg";
        }

        // 3. Last resort fallback
        return 'https://images.unsplash.com/photo-1611162617474-5b21e879e113?w=800&q=80';
    }

    /**
     * Format duration_seconds into "Xh Ym" or "Zm" string.
     */
    public function getDurationLabelAttribute(): string
    {
        $s = $this->resolved_duration_seconds;
        if ($s <= 0 && $this->video_duration) {
            return $this->video_duration;
        }

        if ($s <= 0) return '';
        $h = floor($s / 3600);
        $m = (int) ceil(($s % 3600) / 60);
        if ($h > 0) return "{$h}h {$m}m";
        return max(1, $m) . 'm';
    }

    public function getResolvedDurationSecondsAttribute(): int
    {
        $seconds = (int) ($this->duration_seconds ?? 0);
        if ($seconds > 0) {
            return $seconds;
        }

        return $this->parseDurationToSeconds($this->video_duration);
    }

    private function parseDurationToSeconds(?string $duration): int
    {
        $duration = trim((string) $duration);
        if ($duration === '') {
            return 0;
        }

        if (preg_match('/^PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?$/i', $duration, $matches)) {
            return ((int) ($matches[1] ?? 0) * 3600)
                + ((int) ($matches[2] ?? 0) * 60)
                + (int) ($matches[3] ?? 0);
        }

        if (preg_match('/^\d{1,2}(?::\d{2}){1,2}$/', $duration)) {
            $parts = array_map('intval', explode(':', $duration));
            if (count($parts) === 3) {
                return ($parts[0] * 3600) + ($parts[1] * 60) + $parts[2];
            }

            return ($parts[0] * 60) + $parts[1];
        }

        preg_match_all('/(\d+)\s*(h|hr|hrs|hour|hours|m|min|mins|minute|minutes|s|sec|secs|second|seconds)\b/i', $duration, $matches, PREG_SET_ORDER);
        if ($matches) {
            $seconds = 0;
            foreach ($matches as $match) {
                $value = (int) $match[1];
                $unit = strtolower($match[2]);
                if (str_starts_with($unit, 'h')) {
                    $seconds += $value * 3600;
                } elseif (str_starts_with($unit, 'm')) {
                    $seconds += $value * 60;
                } else {
                    $seconds += $value;
                }
            }

            return $seconds;
        }

        return 0;
    }

    // ── Relationships ──────────────────────────────────────────

    public function watchers()
    {
        return $this->belongsToMany(User::class, 'user_video_progress')
            ->withPivot(['watched_seconds', 'completion_percent', 'completed', 'last_watched_at'])
            ->withTimestamps();
    }

    public function bookmarkers()
    {
        return $this->belongsToMany(User::class, 'bookmarks')
            ->withTimestamps();
    }

    public function progressRecords()
    {
        return $this->hasMany(UserVideoProgress::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function category_rel()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // ── Scopes ─────────────────────────────────────────────────

    protected static function booted()
    {
        static::creating(function ($content) {
            if (!$content->slug) {
                $content->slug = \Illuminate\Support\Str::slug($content->title);
            }
            if (!$content->youtube_id && $content->video_url && (str_contains($content->video_url, 'youtube') || str_contains($content->video_url, 'youtu.be'))) {
                $content->youtube_id = $content->extractYoutubeId($content->video_url);
            }
        });

        static::updating(function ($content) {
            if ($content->isDirty('title') && !$content->isDirty('slug')) {
                $content->slug = \Illuminate\Support\Str::slug($content->title);
            }
            if ($content->isDirty('video_url') && (str_contains($content->video_url, 'youtube') || str_contains($content->video_url, 'youtu.be'))) {
                $content->youtube_id = $content->extractYoutubeId($content->video_url);
            } else if ($content->isDirty('video_url')) {
                $content->youtube_id = null; // Reset if not a YouTube URL
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForUser($query, User $user)
    {
        $interests = $user->interests ?? [];
        if (empty($interests)) return $query->active();

        return $query->active()->where(function ($q) use ($interests) {
            foreach ($interests as $interest) {
                $q->orWhere('category', 'like', "%{$interest}%")
                  ->orWhere('tags', 'like', "%{$interest}%");
            }
        });
    }
}
