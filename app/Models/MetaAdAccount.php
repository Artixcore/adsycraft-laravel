<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MetaAdAccount extends Model
{
    protected $fillable = [
        'business_account_id',
        'meta_ad_account_id',
        'name',
        'currency',
        'account_status',
        'selected',
    ];

    protected function casts(): array
    {
        return [
            'selected' => 'boolean',
        ];
    }

    public function businessAccount(): BelongsTo
    {
        return $this->belongsTo(BusinessAccount::class);
    }
}
