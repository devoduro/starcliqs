<?php

namespace App\Models;

use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    use Publishable;

    protected $fillable = ['name', 'logo', 'website_url', 'product_id', 'sort_order', 'is_published'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /** Where the client's card links: their own website first, otherwise our product page for them. */
    public function link(): ?array
    {
        if ($this->website_url) {
            return ['url' => $this->website_url, 'label' => 'Visit website', 'external' => true];
        }
        if ($this->product && $this->product->is_published) {
            return ['url' => route('products.show', $this->product), 'label' => 'View ' . $this->product->name, 'external' => false];
        }

        return null;
    }

    public function initials(): string
    {
        // A single CamelCase word ("GhCodeHub") uses its capitals: GCH.
        if (! preg_match('/[\s,.\-–]/u', trim($this->name)) && preg_match_all('/\p{Lu}/u', $this->name, $caps) > 1) {
            return implode('', array_slice($caps[0], 0, 3));
        }

        return collect(preg_split('/[\s,.\-–]+/u', $this->name))->filter(fn ($w) => ctype_upper(mb_substr($w, 0, 1)))
            ->take(3)->map(fn ($w) => mb_substr($w, 0, 1))->implode('') ?: mb_strtoupper(mb_substr($this->name, 0, 2));
    }
}
