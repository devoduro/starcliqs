<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use App\Models\Concerns\Publishable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Training extends Model
{
    use HasSlug, Publishable;

    public const MODES = ['in-person' => 'In-person', 'online' => 'Online', 'hybrid' => 'Hybrid'];

    protected $fillable = ['title', 'slug', 'icon', 'level', 'summary', 'body', 'outline', 'duration', 'schedule', 'mode', 'fee', 'starts_on', 'registration_closes_on', 'image', 'sort_order', 'is_published'];

    protected $casts = [
        'outline' => 'array',
        'fee' => 'decimal:2',
        'starts_on' => 'date',
        'registration_closes_on' => 'date',
        'is_published' => 'boolean',
    ];

    /** Registration is open until the closing date (inclusive), or until the start date when none is set. */
    public function registrationOpen(): bool
    {
        $deadline = $this->registration_closes_on ?? $this->starts_on;

        return $deadline === null || ! $deadline->isPast() || $deadline->isToday();
    }

    /** Soonest upcoming first, then undated (rolling) courses, then past runs. */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderByRaw('CASE WHEN starts_on IS NULL THEN 1 WHEN starts_on < CURDATE() THEN 2 ELSE 0 END')
            ->orderBy('starts_on')->orderBy('sort_order');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Enquiry::class);
    }

    public function modeLabel(): string
    {
        return self::MODES[$this->mode] ?? ucfirst($this->mode);
    }

    public function feeLabel(): string
    {
        if ($this->fee === null) {
            return 'Contact us';
        }

        return (float) $this->fee == 0 ? 'Free' : 'GH₵ ' . number_format((float) $this->fee, 2);
    }
}
