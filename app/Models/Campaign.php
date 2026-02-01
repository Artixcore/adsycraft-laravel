<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = ['ad_account_id', 'meta_campaign_id', 'name', 'objective', 'status', 'budget', 'start_time', 'end_time'];

    protected function casts(): array
    {
        return [
            'budget' => 'decimal:2',
            'start_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }

    public function adAccount(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AdAccount::class);
    }

    public function adSets(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AdSet::class);
    }
}
