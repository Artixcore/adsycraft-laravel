<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdSet extends Model
{
    protected $fillable = ['campaign_id', 'meta_ad_set_id', 'name', 'status', 'daily_budget', 'targeting'];

    protected function casts(): array
    {
        return [
            'daily_budget' => 'decimal:2',
            'targeting' => 'array',
        ];
    }

    public function campaign(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function ads(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Ad::class, 'ad_set_id');
    }
}
