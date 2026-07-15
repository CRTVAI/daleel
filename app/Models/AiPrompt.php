<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiPrompt extends Model
{
    protected $fillable = ['key', 'title', 'prompt', 'description'];

    public static function getPrompt(string $key, array $replace = []): string
    {
        $promptObj = self::where('key', $key)->first();
        $prompt = $promptObj ? $promptObj->prompt : '';

        foreach ($replace as $k => $v) {
            $prompt = str_replace('{' . $k . '}', $v, $prompt);
        }

        return $prompt;
    }
}
