<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtensionSession extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'extension_device_id',
        'session_id_from_ext',
        'started_at',
        'ended_at',
        'platform_type',
        'platform_domain',
        'platform_category',
        'is_ai_tool',
        'active_ms',
        'idle_ms',
        'open_ms',
        'click_count',
        'interaction_count',
        'page_count',
        'tab_switch_count',
        'pages',
        'local_signals',
        'recommended_content_tags',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'is_ai_tool' => 'boolean',
        'pages' => 'array',
        'local_signals' => 'array',
        'recommended_content_tags' => 'array',
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
