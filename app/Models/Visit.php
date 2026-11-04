<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['path', 'route_name', 'visitor_id', 'ip_masked', 'device', 'browser', 'platform', 'referrer_host', 'referrer', 'status'];

    protected $casts = [
        'created_at' => 'datetime',
        'status' => 'integer',
    ];

    public function wasBlocked(): bool
    {
        return $this->status === 429;
    }
}
