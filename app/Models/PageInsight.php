<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageInsight extends Model
{
    protected $fillable = [
        'meta_asset_id',
        'business_account_id',
        'period',
        'period_date',
        'metrics',
    ];

    protected function casts(): array
    {
        return [
            'period_date' => 'date',
            'metrics' => 'array',
        ];
    }

    public function metaAsset(): BelongsTo
    {
        return $this->belongsTo(MetaAsset::class);
    }

    public function businessAccount(): BelongsTo
    {
        return $this->belongsTo(BusinessAccount::class);
    }
}
