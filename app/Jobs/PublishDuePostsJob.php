<?php

namespace App\Jobs;

use App\Models\Post;
use App\Models\PostLog;
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

    protected function publishPost(Post $post): void
    {
        $post->update(['status' => Post::STATUS_PUBLISHING]);

        try {
            // Stub: no Meta API call; mark as published and log.
            $providerPostId = 'stub_' . $post->id;

            $post->update([
                'status' => Post::STATUS_PUBLISHED,
                'published_at' => now(),
                'provider_post_id' => $providerPostId,
            ]);

            PostLog::create([
                'post_id' => $post->id,
                'level' => 'info',
                'message' => 'Published (stub)',
                'meta' => ['id' => $providerPostId, 'success' => true],
            ]);
        } catch (\Throwable $e) {
            $post->update([
                'status' => Post::STATUS_FAILED,
                'error_message' => $e->getMessage(),
            ]);

            PostLog::create([
                'post_id' => $post->id,
                'level' => 'error',
                'message' => $e->getMessage(),
                'meta' => ['error' => $e->getMessage()],
            ]);

            Log::error('PublishDuePostsJob: failed to publish post', [
                'post_id' => $post->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('PublishDuePostsJob: job failed', [
            'exception' => $exception->getMessage(),
        ]);
    }
}
