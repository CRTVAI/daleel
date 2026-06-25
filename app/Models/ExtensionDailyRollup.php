<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtensionDailyRollup extends Model
{
    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
        'top_platforms' => 'array',
        'top_ai_tools' => 'array',
        'student_learning_needs' => 'array',
    ];

    public function device()
    {
        return $this->belongsTo(ExtensionDevice::class, 'extension_device_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
