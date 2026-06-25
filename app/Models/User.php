<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'is_admin',
        'account_type',
        'learning_goal',
        'experience_level',
        'interests',
        'connections',
        'streak_count',
        'last_activity_at',
        'parent_id',
        'department_id',
        'is_employee',
        'connection_code',
        'connection_code_issued_at',
        'can_access_team',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'interests'         => 'array',
            'connections'       => 'array',
            'is_admin'                   => 'boolean',
            'is_employee'                => 'boolean',
            'can_access_team'            => 'boolean',
            'last_activity_at'           => 'datetime',
            'connection_code_issued_at'  => 'datetime',
        ];
    }

    // ── Relationships ──────────────────────────────────────────

    public function employees()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    public function company()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function videoProgress()
    {
        return $this->hasMany(UserVideoProgress::class);
    }

    public function watchedContents()
    {
        return $this->belongsToMany(Content::class, 'user_video_progress')
            ->withPivot(['watched_seconds', 'completion_percent', 'completed', 'last_watched_at'])
            ->withTimestamps();
    }

    public function bookmarkedContents()
    {
        return $this->belongsToMany(Content::class, 'bookmarks')
            ->withTimestamps();
    }

    /**
     * Update the user's learning streak.
     */
    public function recordActivity()
    {
        $today = now()->startOfDay();
        $lastActivity = $this->last_activity_at ? $this->last_activity_at->startOfDay() : null;

        if (!$lastActivity) {
            // First time activity
            $this->streak_count = 1;
        } else {
            $diff = $today->diffInDays($lastActivity);

            if ($diff == 1) {
                // Continued streak
                $this->streak_count += 1;
            } elseif ($diff > 1) {
                // Streak broken
                $this->streak_count = 1;
            }
            // If diff == 0, already recorded today, do nothing
        }

        $this->last_activity_at = now();
        $this->save();
    }

    // ── Helpers ────────────────────────────────────────────────

    /**
     * Total watch time in seconds across all videos.
     */
    public function totalWatchSeconds(): int
    {
        return $this->videoProgress()->sum('watched_seconds');
    }

    /**
     * Number of fully completed videos.
     */
    public function completedVideosCount(): int
    {
        return $this->videoProgress()->where('completed', true)->count();
    }

    /**
     * Format seconds into "Xh Ym" string.
     */
    public static function formatSeconds(int $seconds): string
    {
        $h = floor($seconds / 3600);
        $m = floor(($seconds % 3600) / 60);
        if ($h > 0) return "{$h}h {$m}m";
        return "{$m}m";
    }

    /**
     * Check if the user's learning profile is incomplete.
     */
    public function hasIncompleteProfile(): bool
    {
        return empty($this->learning_goal) || empty($this->experience_level) || empty($this->interests);
    }
    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\VerifyEmailCustom);
    }
}
