<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookEvent extends Model
{
    public const TYPE_MESSAGE = 'message';

    public const TYPE_COMMENT = 'comment';

    public const TYPE_POST_REACTION = 'post_reaction';

    public const TYPE_PAGE_FEED = 'page_feed';

    public const TYPE_ACCOUNT_INSIGHTS = 'account_insights';

    public const STATUS_PENDING = 'pending';

    public const STATUS_PROCESSED = 'processed';

    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'source',
        'event_type',
        'payload',
        'processing_status',
        'error_message',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'processed_at' => 'datetime',
        ];
    }
}
