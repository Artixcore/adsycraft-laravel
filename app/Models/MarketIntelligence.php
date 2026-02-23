<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketIntelligence extends Model
{
    protected $table = 'market_intelligence';

    protected $fillable = [
        'business_account_id',
        'research_output',
        'trend_output',
        'competitor_ad_data',
        'refreshed_at',
    ];

    protected function casts(): array
    {
        return [
            'research_output' => 'array',
            'trend_output' => 'array',
            'competitor_ad_data' => 'array',
            'refreshed_at' => 'datetime',
        ];
    }

    public function businessAccount(): BelongsTo
    {
        return $this->belongsTo(BusinessAccount::class);
    }
}
