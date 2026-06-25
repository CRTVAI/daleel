<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtensionMetricsSnapshot extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'extension_device_id',
        'captured_at',
        'window_minutes',
        'focus_score',
        'productivity_score',
        'ai_adoption_score',
        'workflow_efficiency_score',
        'active_ms',
        'idle_ms',
        'context_switch_count',
        'tab_switches_per_hour',
        'top_platforms',
        'detected_patterns',
    ];

    protected $casts = [
        'captured_at' => 'datetime',
        'top_platforms' => 'array',
        'detected_patterns' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function device()
    {
        return $this->belongsTo(ExtensionDevice::class, 'extension_device_id');
    }
}
