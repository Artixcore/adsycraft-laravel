<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PublishPostRequest;
use App\Http\Requests\SchedulePostRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\BusinessAccount;
use App\Models\Post;
use App\Services\AI\AIManager;
use App\Services\AI\StubCaptionGenerator;
use App\Services\Meta\MetaPublishingService;
use App\Support\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'view', $business);

        $request->validate([
            'status' => ['sometimes', 'string', 'in:scheduled,published,failed,draft,publishing,cancelled'],
            'meta_asset_id' => ['sometimes', 'integer', 'exists:meta_assets,id'],
            'from' => ['sometimes', 'date_format:Y-m-d'],
            'to' => ['sometimes', 'date_format:Y-m-d'],
        ]);

        $query = $business->posts()->orderBy('scheduled_at');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('meta_asset_id')) {
            $query->where('meta_asset_id', $request->input('meta_asset_id'));
        }
        if ($request->filled('from')) {
            $query->where('scheduled_at', '>=', Carbon::parse($request->input('from'))->startOfDay());
        }
        if ($request->filled('to')) {
            $query->where('scheduled_at', '<=', Carbon::parse($request->input('to'))->endOfDay());
        }

        $items = $query->get();
        $total = $items->count();
        $end = $total > 0 ? $total - 1 : 0;

        return ApiResponse::success($items, 'OK', ['total' => $total])
            ->header('Content-Range', "posts 0-{$end}/{$total}");
    }

    public function store(StorePostRequest $request, BusinessAccount $business): JsonResponse
    {
        $data = array_merge($request->validated(), [
            'business_account_id' => $business->id,
            'status' => Post::STATUS_DRAFT,
            'scheduled_at' => now(),
        ]);
        $post = Post::create($data);

        return response()->json(['data' => $post], 201);
    }

    public function show(Request $request, BusinessAccount $business, Post $post): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'view', $business);
        $this->ensurePostBelongsToBusiness($post, $business);

        return ApiResponse::success($post->load(['metaAsset', 'contentPillar']));
    }

    public function update(UpdatePostRequest $request, BusinessAccount $business, Post $post): JsonResponse
    {
        $this->ensurePostBelongsToBusiness($post, $business);
        $post->update($request->validated());

        return ApiResponse::success($post->fresh(), 'Post updated.');
    }

    public function destroy(Request $request, BusinessAccount $business, Post $post): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'update', $business);
        $this->ensurePostBelongsToBusiness($post, $business);
        $post->delete();

        return ApiResponse::success(null, 'Post deleted.', null, 200);
    }

    public function schedule(SchedulePostRequest $request, BusinessAccount $business, Post $post): JsonResponse
    {
        $this->ensurePostBelongsToBusiness($post, $business);
        if ($post->status !== Post::STATUS_DRAFT) {
            return ApiResponse::error('Only drafts can be scheduled.', null, 422);
        }
        $scheduledAt = Carbon::parse($request->input('scheduled_at'), $request->input('timezone') ?? $business->timezone ?? 'UTC');
        $post->update([
            'meta_asset_id' => $request->input('meta_asset_id'),
            'scheduled_at' => $scheduledAt,
            'status' => Post::STATUS_SCHEDULED,
        ]);

        return ApiResponse::success($post->fresh(), 'Post scheduled.');
    }

    public function publishNow(PublishPostRequest $request, BusinessAccount $business, Post $post): JsonResponse
    {
        $this->ensurePostBelongsToBusiness($post, $business);
        if ($post->status !== Post::STATUS_DRAFT) {
            return response()->json(['message' => 'Only drafts can be published now.'], 422);
        }
        $post->update([
            'meta_asset_id' => $request->input('meta_asset_id'),
            'scheduled_at' => now(),
        ]);

        $post = MetaPublishingService::make()->publishAndUpdatePost($post);

        return ApiResponse::success($post, 'Post published.');
    }

    public function metrics(Request $request, BusinessAccount $business, Post $post): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'view', $business);
        $this->ensurePostBelongsToBusiness($post, $business);

        $metric = $post->postMetric;

        $data = $metric ? [
            'reach' => $metric->reach,
            'impressions' => $metric->impressions,
            'likes' => $metric->likes,
            'comments' => $metric->comments,
            'shares' => $metric->shares,
            'saves' => $metric->saves,
            'engagement_rate' => $metric->engagement_rate,
            'fetched_at' => $metric->fetched_at?->toIso8601String(),
        ] : [
            'reach' => 0,
            'impressions' => 0,
            'likes' => 0,
            'comments' => 0,
            'shares' => 0,
            'saves' => 0,
            'engagement_rate' => null,
            'fetched_at' => null,
        ];

        return ApiResponse::success($data);
    }

    public function regenerate(Request $request, BusinessAccount $business, Post $post): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'update', $business);
        $this->ensurePostBelongsToBusiness($post, $business);

        if (! in_array($post->status, [Post::STATUS_DRAFT, Post::STATUS_SCHEDULED], true)) {
            return ApiResponse::error('Only draft or scheduled posts can be regenerated.', null, 422);
        }

        $captions = [];
        if (app(AIManager::class)->hasConfiguredProvider()) {
            $captions = app(StubCaptionGenerator::class)->generate($post->businessAccount, 1, $post->scheduled_at?->toDateString() ?? now()->toDateString());
        }

        $caption = $captions[0] ?? 'Regenerated placeholder caption';

        $post->update(['caption' => $caption]);

        return ApiResponse::success($post->fresh(['postMetric']), 'Caption regenerated.');
    }

    private function ensurePostBelongsToBusiness(Post $post, BusinessAccount $business): void
    {
        if ($post->business_account_id !== $business->id) {
            abort(404);
        }
    }
}
