<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasSlug, Publishable;

    protected $fillable = ['title', 'slug', 'client', 'category', 'summary', 'body', 'technologies', 'image', 'project_url', 'completed_on', 'is_featured', 'is_published'];

    protected $casts = [
        'technologies' => 'array',
        'completed_on' => 'date',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
    ];

    /** Projects have no manual order: featured first, then most recently completed. */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByDesc('is_featured')->orderByDesc('completed_on')->orderByDesc('id');
    }
}
