<?php

namespace App\Services\Meta;

use App\Models\MetaAsset;
use App\Models\Post;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetaPublishingService
{
    public function __construct(
        private readonly string $graphVersion
    ) {}

    public static function make(): self
    {
        return new self(config('services.meta.graph_version', 'v21.0'));
    }

    /**
     * Publish a post to the selected Meta assets (Page and/or Instagram).
     *
     * @return array{facebook?: array{post_id: string}, instagram?: array{media_id: string}}
     */
    public function publish(Post $post): array
    {
        $asset = $post->metaAsset;
        if (! $asset) {
            throw new \InvalidArgumentException('Post must have a meta asset to publish.');
        }

        $results = [];

        if (config('services.meta.stub', false)) {
            return $this->stubPublish($asset);
        }

        $pageToken = $asset->page_access_token;
        if (! $pageToken) {
            throw new \RuntimeException('Page access token is missing. Reconnect Meta in Connectors.');
        }

        $content = [
            'message' => $post->caption ?? '',
            'media_url' => $post->media_url,
        ];

        if ($asset->page_id) {
            try {
                $fbResult = $this->publishToPage($asset, $content);
                $results['facebook'] = $fbResult;
            } catch (\Throwable $e) {
                Log::warning('Meta publish to Page failed', [
                    'post_id' => $post->id,
                    'page_id' => $asset->page_id,
                    'error' => $e->getMessage(),
                ]);
                throw $e;
            }
        }

        if ($asset->ig_business_id && $content['media_url']) {
            try {
                $igResult = $this->publishToInstagram($asset, $content);
                $results['instagram'] = $igResult;
            } catch (\Throwable $e) {
                Log::warning('Meta publish to Instagram failed', [
                    'post_id' => $post->id,
                    'ig_id' => $asset->ig_business_id,
                    'error' => $e->getMessage(),
                ]);
                throw $e;
            }
        }

        return $results;
    }

    /**
     * Publish a post to Meta and update its status. Used by PostController and PublishDuePostsJob.
     */
    public function publishAndUpdatePost(Post $post): Post
    {
        $post->update(['status' => Post::STATUS_PUBLISHING]);

        try {
            $results = $this->publish($post);

            $providerPostId = $results['facebook']['post_id'] ?? $results['instagram']['media_id'] ?? 'stub_'.$post->id;

            $post->update([
                'status' => Post::STATUS_PUBLISHED,
                'published_at' => now(),
                'provider_post_id' => $providerPostId,
            ]);

            \App\Models\PostLog::create([
                'post_id' => $post->id,
                'level' => 'info',
                'message' => 'Published to Meta',
                'meta' => array_merge(['id' => $providerPostId, 'success' => true], $results),
            ]);

            return $post->fresh();
        } catch (\Throwable $e) {
            $post->update([
                'status' => Post::STATUS_FAILED,
                'error_message' => $e->getMessage(),
            ]);

            \App\Models\PostLog::create([
                'post_id' => $post->id,
                'level' => 'error',
                'message' => $e->getMessage(),
                'meta' => ['error' => $e->getMessage()],
            ]);

            throw $e;
        }
    }

    /**
     * Publish to Facebook Page.
     *
     * @param  array{message: string, media_url?: string|null}  $content
     * @return array{post_id: string}
     */
    public function publishToPage(MetaAsset $asset, array $content): array
    {
        if (config('services.meta.stub', false)) {
            return ['post_id' => 'stub_fb_'.bin2hex(random_bytes(4))];
        }

        $token = $asset->page_access_token;
        if (! $token) {
            throw new \RuntimeException('Page access token is missing.');
        }

        $url = "https://graph.facebook.com/{$this->graphVersion}/{$asset->page_id}/";
        $endpoint = ! empty($content['media_url']) ? 'photos' : 'feed';
        $url .= $endpoint;

        $params = [
            'message' => $content['message'] ?? '',
            'access_token' => $token,
        ];

        if ($endpoint === 'photos' && ! empty($content['media_url'])) {
            $params['url'] = $content['media_url'];
            $params['caption'] = $content['message'] ?? '';
        }

        $response = Http::post($url, $params);

        if (! $response->successful()) {
            $error = $response->json('error', []);
            throw new \RuntimeException(
                $error['message'] ?? 'Facebook API error: '.$response->body()
            );
        }

        $data = $response->json();
        $postId = $data['id'] ?? $data['post_id'] ?? null;

        if (! $postId) {
            throw new \RuntimeException('Facebook API did not return post ID.');
        }

        return ['post_id' => (string) $postId];
    }

    /**
     * Publish to Instagram (requires image or video URL).
     *
     * @param  array{message: string, media_url?: string|null}  $content
     * @return array{media_id: string}
     */
    public function publishToInstagram(MetaAsset $asset, array $content): array
    {
        if (config('services.meta.stub', false)) {
            return ['media_id' => 'stub_ig_'.bin2hex(random_bytes(4))];
        }

        $mediaUrl = $content['media_url'] ?? null;
        if (! $mediaUrl) {
            throw new \InvalidArgumentException('Instagram requires a media URL (image or video).');
        }

        $token = $asset->page_access_token;
        if (! $token) {
            throw new \RuntimeException('Page access token is missing.');
        }

        $igUserId = $asset->ig_business_id;
        $host = 'https://graph.facebook.com';

        $containerParams = [
            'image_url' => $mediaUrl,
            'caption' => $content['message'] ?? '',
            'access_token' => $token,
        ];

        $containerResponse = Http::post(
            "{$host}/{$this->graphVersion}/{$igUserId}/media",
            $containerParams
        );

        if (! $containerResponse->successful()) {
            $error = $containerResponse->json('error', []);
            throw new \RuntimeException(
                $error['message'] ?? 'Instagram container creation failed: '.$containerResponse->body()
            );
        }

        $containerId = $containerResponse->json('id');
        if (! $containerId) {
            throw new \RuntimeException('Instagram API did not return container ID.');
        }

        $publishResponse = Http::post(
            "{$host}/{$this->graphVersion}/{$igUserId}/media_publish",
            [
                'creation_id' => $containerId,
                'access_token' => $token,
            ]
        );

        if (! $publishResponse->successful()) {
            $error = $publishResponse->json('error', []);
            throw new \RuntimeException(
                $error['message'] ?? 'Instagram publish failed: '.$publishResponse->body()
            );
        }

        $mediaId = $publishResponse->json('id');
        if (! $mediaId) {
            throw new \RuntimeException('Instagram API did not return media ID.');
        }

        return ['media_id' => (string) $mediaId];
    }

    /**
     * @return array{facebook?: array{post_id: string}, instagram?: array{media_id: string}}
     */
    private function stubPublish(MetaAsset $asset): array
    {
        $results = [];

        if ($asset->page_id) {
            $results['facebook'] = ['post_id' => 'stub_fb_'.bin2hex(random_bytes(4))];
        }
        if ($asset->ig_business_id) {
            $results['instagram'] = ['media_id' => 'stub_ig_'.bin2hex(random_bytes(4))];
        }

        return $results;
    }
}
