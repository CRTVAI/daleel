<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtensionHelpRequest extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function device()
    {
        return $this->belongsTo(ExtensionDevice::class, 'extension_device_id');
    }
}
