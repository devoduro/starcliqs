<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasSlug;

    protected $fillable = ['title', 'slug', 'category', 'excerpt', 'body', 'cover_image', 'author', 'published_at'];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function isLive(): bool
    {
        return $this->published_at !== null && $this->published_at->lte(now());
    }

    public function excerptText(int $limit = 160): string
    {
        return $this->excerpt ?: Str::limit(trim(strip_tags($this->body)), $limit);
    }

    public function readingMinutes(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags($this->body)) / 200));
    }
}
