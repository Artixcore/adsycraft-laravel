<?php

namespace App\Http\Controllers;

use App\Models\WebhookEvent;
use App\Services\Webhooks\CommentWebhookProcessor;
use App\Services\Webhooks\MessageWebhookProcessor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class MetaWebhookController extends Controller
{
    public function __construct(
        private readonly MessageWebhookProcessor $messageProcessor,
        private readonly CommentWebhookProcessor $commentProcessor
    ) {}

    public function verify(Request $request): Response
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        $expectedToken = config('services.meta.webhook_verify_token');

        if ($mode === 'subscribe' && $expectedToken && $token === $expectedToken) {
            return response($challenge ?? '', 200);
        }

        return response('', 403);
    }

    public function handle(Request $request): Response
    {
        $payload = $request->all();
        $eventType = $this->determineEventType($payload);

        $event = WebhookEvent::create([
            'source' => 'meta',
            'event_type' => $eventType,
            'payload' => $payload,
            'processing_status' => WebhookEvent::STATUS_PENDING,
        ]);

        try {
            $this->dispatchProcessor($event);
        } catch (\Throwable $e) {
            Log::error('Meta webhook processing failed', ['event_id' => $event->id, 'error' => $e->getMessage()]);
            $event->update([
                'processing_status' => WebhookEvent::STATUS_FAILED,
                'error_message' => $e->getMessage(),
            ]);
        }

        return response('', 200);
    }

    private function determineEventType(array $payload): ?string
    {
        if (isset($payload['entry']) && is_array($payload['entry'])) {
            foreach ($payload['entry'] as $entry) {
                if (isset($entry['messaging'])) {
                    return WebhookEvent::TYPE_MESSAGE;
                }
                if (isset($entry['changes'])) {
                    foreach ($entry['changes'] as $change) {
                        if (($change['field'] ?? '') === 'comments') {
                            return WebhookEvent::TYPE_COMMENT;
                        }
                        if (($change['field'] ?? '') === 'feed') {
                            return WebhookEvent::TYPE_PAGE_FEED;
                        }
                    }
                }
            }
        }

        return null;
    }

    private function dispatchProcessor(WebhookEvent $event): void
    {
        match ($event->event_type) {
            WebhookEvent::TYPE_MESSAGE => $this->messageProcessor->process($event),
            WebhookEvent::TYPE_COMMENT => $this->commentProcessor->process($event),
            default => $event->update([
                'processing_status' => WebhookEvent::STATUS_PROCESSED,
                'processed_at' => now(),
            ]),
        };
    }
}
