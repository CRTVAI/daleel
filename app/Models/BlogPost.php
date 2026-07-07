<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'cover_image',
        'category',
        'tags',
        'author_name',
        'author_avatar',
        'read_time_minutes',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
        'is_featured',
        'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
            if (empty($post->published_at) && $post->status === 'published') {
                $post->published_at = now();
            }
        });
    }

    public function getParsedContentAttribute()
    {
        // Simple HTML helper for rendering markdown/newlines if needed.
        return nl2br(e($this->content));
    }
}
