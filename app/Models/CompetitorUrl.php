<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitorUrl extends Model
{
    protected $fillable = [
        'business_account_id',
        'url',
        'page_name',
        'meta_page_id',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function businessAccount(): BelongsTo
    {
        return $this->belongsTo(BusinessAccount::class);
    }
}
