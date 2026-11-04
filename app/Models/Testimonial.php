<?php

namespace App\Models;

use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use Publishable;

    protected $fillable = ['name', 'role', 'company', 'quote', 'photo', 'rating', 'sort_order', 'is_published'];

    protected $casts = [
        'rating' => 'integer',
        'is_published' => 'boolean',
    ];

    public function initials(): string
    {
        return collect(explode(' ', $this->name))->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->implode('');
    }
}
