<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'title',
        'description',
        'thumbnail',
        'category',
        'category_id',
        'status',
    ];

    /**
     * Get all content items associated with this course.
     */
    public function contents(): HasMany
    {
        return $this->hasMany(Content::class)->orderBy('sort_order');
    }

    public function category_rel()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get content grouped by section/part label.
     */
    public function getGroupedContentsAttribute()
    {
        return $this->contents->groupBy('section_part_label');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
