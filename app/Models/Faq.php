<?php

namespace App\Models;

use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use Publishable;

    protected $fillable = ['topic', 'icon', 'question', 'answer', 'sort_order', 'is_published'];

    protected $casts = [
        'is_published' => 'boolean',
    ];
}
