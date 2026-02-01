<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReplyMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $conversationId,
        public string $text
    ) {}

    public function handle(): void
    {
        $conversation = \App\Models\Conversation::find($this->conversationId);
        if (! $conversation) {
            return;
        }
        \App\Models\InboxMessage::create([
            'conversation_id' => $conversation->id,
            'text' => $this->text,
            'direction' => 'out',
        ]);
    }
}
