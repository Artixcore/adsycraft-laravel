<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $fillable = ['ad_set_id', 'meta_ad_id', 'name', 'status', 'creative'];

    protected function casts(): array
    {
        return ['creative' => 'array'];
    }

    public function adSet(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AdSet::class);
    }
}
