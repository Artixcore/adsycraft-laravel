<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductResearch extends Model
{
    protected $table = 'product_research';

    protected $fillable = [
        'meta_asset_id',
        'business_account_id',
        'product_name',
        'description',
        'price_hints',
        'pain_points',
        'sources',
        'confidence',
    ];

    protected function casts(): array
    {
        return [
            'price_hints' => 'array',
            'pain_points' => 'array',
            'sources' => 'array',
            'confidence' => 'decimal:4',
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
