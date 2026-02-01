<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';

    public const STATUS_SCHEDULED = 'scheduled';

    public const STATUS_PUBLISHING = 'publishing';

    public const STATUS_PUBLISHED = 'published';

    public const STATUS_FAILED = 'failed';

    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'business_account_id',
        'meta_asset_id',
        'content_pillar_id',
        'channel',
        'caption',
        'media_type',
        'media_prompt',
        'media_url',
        'scheduled_at',
        'published_at',
        'status',
        'provider_post_id',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function businessAccount(): BelongsTo
    {
        return $this->belongsTo(BusinessAccount::class);
    }

    public function metaAsset(): BelongsTo
    {
        return $this->belongsTo(MetaAsset::class);
    }

    public function contentPillar(): BelongsTo
    {
        return $this->belongsTo(ContentPillar::class);
    }

    public function postLogs(): HasMany
    {
        return $this->hasMany(PostLog::class);
    }
}
