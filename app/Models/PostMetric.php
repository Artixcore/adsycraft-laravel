<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostMetric extends Model
{
    protected $fillable = [
        'post_id',
        'reach',
        'impressions',
        'likes',
        'comments',
        'shares',
        'saves',
        'engagement_rate',
        'fetched_at',
    ];

    protected function casts(): array
    {
        return [
            'fetched_at' => 'datetime',
            'engagement_rate' => 'float',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
