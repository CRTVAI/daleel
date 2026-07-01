<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProTrial extends Model
{
    protected $fillable = [
        'user_id', 'name', 'email', 'phone',
        'activated_at', 'expires_at', 'status', 'notes',
    ];

    protected $casts = [
        'activated_at' => 'datetime',
        'expires_at'   => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
