<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PublishPostRequest;
use App\Http\Requests\SchedulePostRequest;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\BusinessAccount;
use App\Models\Post;
use App\Models\PostLog;
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
        ]);

        $query = $business->posts()->orderBy('scheduled_at');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('meta_asset_id')) {
            $query->where('meta_asset_id', $request->input('meta_asset_id'));
        }

        $items = $query->get();
        $total = $items->count();
        $end = $total > 0 ? $total - 1 : 0;

        return response()->json(['data' => $items])
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

        return response()->json(['data' => $post->load(['metaAsset', 'contentPillar'])]);
    }

    public function update(UpdatePostRequest $request, BusinessAccount $business, Post $post): JsonResponse
    {
        $this->ensurePostBelongsToBusiness($post, $business);
        $post->update($request->validated());

        return response()->json(['data' => $post->fresh()]);
    }

    public function destroy(Request $request, BusinessAccount $business, Post $post): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'update', $business);
        $this->ensurePostBelongsToBusiness($post, $business);
        $post->delete();

        return response()->json(null, 204);
    }

    public function schedule(SchedulePostRequest $request, BusinessAccount $business, Post $post): JsonResponse
    {
        $this->ensurePostBelongsToBusiness($post, $business);
        if ($post->status !== Post::STATUS_DRAFT) {
            return response()->json(['message' => 'Only drafts can be scheduled.'], 422);
        }
        $scheduledAt = Carbon::parse($request->input('scheduled_at'), $request->input('timezone') ?? $business->timezone ?? 'UTC');
        $post->update([
            'meta_asset_id' => $request->input('meta_asset_id'),
            'scheduled_at' => $scheduledAt,
            'status' => Post::STATUS_SCHEDULED,
        ]);

        return response()->json(['data' => $post->fresh()]);
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
            'status' => Post::STATUS_PUBLISHING,
        ]);
        $this->publishPost($post);
        $post->refresh();

        return response()->json(['data' => $post]);
    }

    public function calendar(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'view', $business);

        $request->validate([
            'from' => ['sometimes', 'date_format:Y-m-d'],
            'to' => ['sometimes', 'date_format:Y-m-d'],
        ]);

        $query = $business->posts()->orderBy('scheduled_at');

        if ($request->filled('from')) {
            $query->where('scheduled_at', '>=', Carbon::parse($request->input('from'))->startOfDay());
        }
        if ($request->filled('to')) {
            $query->where('scheduled_at', '<=', Carbon::parse($request->input('to'))->endOfDay());
        }

        return response()->json(['data' => $query->get()]);
    }

    private function ensurePostBelongsToBusiness(Post $post, BusinessAccount $business): void
    {
        if ($post->business_account_id !== $business->id) {
            abort(404);
        }
    }

    private function publishPost(Post $post): void
    {
        try {
            $providerPostId = 'stub_'.$post->id;
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
            throw $e;
        }
    }
}
