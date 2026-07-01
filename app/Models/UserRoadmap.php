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
}
