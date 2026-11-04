<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasSlug, Publishable;

    protected $fillable = ['title', 'slug', 'icon', 'summary', 'body', 'features', 'image', 'sort_order', 'is_published'];

    protected $casts = [
        'features' => 'array',
        'is_published' => 'boolean',
    ];
}
