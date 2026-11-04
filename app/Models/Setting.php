<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Every setting as key => value, cached until the next save. Empty before the table exists so
     * the site still renders during a first install.
     */
    public static function values(): array
    {
        return Cache::rememberForever('site_settings', function () {
            return Schema::hasTable('settings') ? static::pluck('value', 'key')->all() : [];
        });
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        $value = static::values()[$key] ?? null;

        return ($value === null || $value === '') ? $default : $value;
    }

    /** A multi-line setting split into its non-empty lines. */
    public static function lines(string $key): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) static::get($key)))));
    }

    public static function put(array $values): void
    {
        foreach ($values as $key => $value) {
            static::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        Cache::forget('site_settings');
    }
}
