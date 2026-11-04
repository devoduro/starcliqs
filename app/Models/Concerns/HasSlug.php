<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Fills `slug` from the model's title column when it is left blank, adding -2, -3 ... until it is
 * unique. Models set $slugSource when their title column is not `title`.
 */
trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::saving(function ($model) {
            $source = $model->slugSource ?? 'title';
            $base = Str::slug($model->slug ?: $model->{$source}) ?: Str::random(8);

            $slug = $base;
            $i = 2;
            while (static::where('slug', $slug)->when($model->exists, fn ($q) => $q->whereKeyNot($model->getKey()))->exists()) {
                $slug = $base . '-' . $i++;
            }
            $model->slug = $slug;
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
