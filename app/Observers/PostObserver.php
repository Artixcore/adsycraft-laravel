<?php

namespace App\Observers;

use App\Models\Post;
use App\Services\ContentQualityService;

class PostObserver
{
    public function __construct(
        private ContentQualityService $qualityService
    ) {}

    /**
     * Handle the Post "saving" event.
     */
    public function saving(Post $post): void
    {
        if ($post->isDirty('caption') || $post->quality_score === null) {
            $post->quality_score = $this->qualityService->calculate($post);
        }
    }

    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
        //
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
        //
    }
}
