<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdLibraryResultsCache extends Model
{
    protected $table = 'ad_library_results_cache';

    protected $fillable = [
        'ad_library_search_id',
        'cache_key',
        'payload',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'payload' => 'array',
        ];
    }

    public function adLibrarySearch(): BelongsTo
    {
        return $this->belongsTo(AdLibrarySearch::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
