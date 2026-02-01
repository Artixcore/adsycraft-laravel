<?php

namespace App\Services\Webhooks;

use App\Models\WebhookEvent;
use Illuminate\Support\Facades\Log;

class CommentWebhookProcessor
{
    public function process(WebhookEvent $event): void
    {
        Log::info('CommentWebhookProcessor: processing event', ['event_id' => $event->id]);

        $event->update([
            'processing_status' => WebhookEvent::STATUS_PROCESSED,
            'processed_at' => now(),
        ]);
    }
}
