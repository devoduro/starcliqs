<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasSlug, Publishable;

    protected $slugSource = 'name';

    protected $fillable = ['name', 'slug', 'tagline', 'icon', 'summary', 'body', 'features', 'image', 'demo_url', 'price_label', 'is_featured', 'sort_order', 'is_published'];

    protected $casts = [
        'features' => 'array',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
    ];

    /** The website link as people read it, e.g. "clickvotegh.com". */
    public function linkLabel(): ?string
    {
        $host = $this->demo_url ? parse_url($this->demo_url, PHP_URL_HOST) : null;

        return $host ? preg_replace('/^www\./', '', $host) : null;
    }
}
