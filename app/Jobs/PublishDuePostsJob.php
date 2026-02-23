<?php

namespace App\Jobs;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PublishDuePostsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [60, 300, 900];

    public int $maxExceptions = 2;

    public function handle(): void
    {
        $duePosts = Post::query()
            ->where('status', Post::STATUS_SCHEDULED)
            ->where('scheduled_at', '<=', Carbon::now())
            ->with('businessAccount')
            ->get();

        foreach ($duePosts as $post) {
            $this->publishPost($post);
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('PublishDuePostsJob: job failed', [
            'exception' => $exception->getMessage(),
        ]);
    }
}
