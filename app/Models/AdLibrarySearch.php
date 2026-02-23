<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdLibrarySearch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_account_id',
        'query',
        'countries',
        'ad_active_status',
        'media_type',
        'platform',
        'started_after',
        'started_before',
        'search_page_ids',
        'ad_type',
        'last_run_at',
    ];

    protected function casts(): array
    {
        return [
            'countries' => 'array',
            'search_page_ids' => 'array',
            'started_after' => 'date',
            'started_before' => 'date',
            'last_run_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function businessAccount(): BelongsTo
    {
        return $this->belongsTo(BusinessAccount::class);
    }

    public function resultsCaches(): HasMany
    {
        return $this->hasMany(AdLibraryResultsCache::class);
    }
}
