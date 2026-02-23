<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdLibraryCollection;
use App\Models\AdLibraryCollectionItem;
use App\Models\AdLibrarySearch;
use App\Services\AdLibrary\AdLibraryCacheService;
use App\Services\AdLibrary\AdLibraryPolicyGuard;
use App\Services\AdLibrary\AdLibraryTokenResolver;
use App\Services\Meta\MetaAdLibraryClient;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdLibraryController extends Controller
{
    public function __construct(
        private readonly AdLibraryCacheService $cacheService,
        private readonly AdLibraryPolicyGuard $policyGuard,
        private readonly AdLibraryTokenResolver $tokenResolver
    ) {}

    public function config(Request $request): JsonResponse
    {
        $enabled = config('services.meta.ad_library.enabled', false);
        if (! $enabled) {
            return ApiResponse::error('Ad Library is not enabled.', null, 403);
        }

        $data = [
            'enabled' => true,
            'default_country' => config('services.meta.ad_library.default_country', 'US'),
            'allowed_countries' => $this->getAllowedCountries(),
            'ad_active_status_options' => ['ACTIVE', 'INACTIVE', 'ALL'],
            'ad_type_options' => ['ALL', 'POLITICAL_AND_ISSUE_ADS', 'EMPLOYMENT_ADS', 'HOUSING_ADS', 'FINANCIAL_PRODUCTS_AND_SERVICES_ADS'],
            'media_type_options' => ['ALL', 'IMAGE', 'VIDEO', 'MEME', 'NONE'],
            'publisher_platforms' => ['facebook', 'instagram', 'audience_network', 'messenger'],
            'disclaimer' => $this->policyGuard->getDisclaimerText(),
        ];

        return ApiResponse::success($data);
    }

    public function search(Request $request): JsonResponse
    {
        if (! config('services.meta.ad_library.enabled', false)) {
            return ApiResponse::error('Ad Library is not enabled.', null, 403);
        }

        $token = $this->tokenResolver->resolveForUser($request->user());
        if (! $token) {
            return ApiResponse::error('Connect Meta to use Ad Library. Go to Connectors and connect your Meta account.', null, 401);
        }

        $validated = $request->validate([
            'query' => ['nullable', 'string', 'max:100'],
            'search_page_ids' => ['nullable', 'array'],
            'search_page_ids.*' => ['string'],
            'countries' => ['required', 'array', 'min:1'],
            'countries.*' => ['string', 'size:2'],
            'ad_active_status' => ['nullable', 'string', 'in:ACTIVE,INACTIVE,ALL'],
            'started_after' => ['nullable', 'date'],
            'started_before' => ['nullable', 'date'],
            'media_type' => ['nullable', 'string', 'in:ALL,IMAGE,VIDEO,MEME,NONE'],
            'publisher_platforms' => ['nullable', 'array'],
            'publisher_platforms.*' => ['string'],
            'ad_type' => ['nullable', 'string'],
            'after' => ['nullable', 'string'],
        ], [
            'countries.required' => 'At least one country is required.',
        ]);

        $params = [
            'search_terms' => $validated['query'] ?? '',
            'search_page_ids' => $validated['search_page_ids'] ?? null,
            'ad_reached_countries' => $validated['countries'],
            'ad_active_status' => $validated['ad_active_status'] ?? 'ACTIVE',
            'ad_delivery_date_min' => $validated['started_after'] ?? null,
            'ad_delivery_date_max' => $validated['started_before'] ?? null,
            'media_type' => $validated['media_type'] ?? null,
            'publisher_platforms' => $validated['publisher_platforms'] ?? null,
            'ad_type' => $validated['ad_type'] ?? null,
            'after' => $validated['after'] ?? null,
        ];

        $hasSearch = ! empty($params['search_terms']) || ! empty($params['search_page_ids']);
        if (! $hasSearch) {
            return ApiResponse::error('Provide either keywords (query) or page IDs to search.', null, 422);
        }

        $cacheKey = AdLibraryCacheService::buildCacheKey($params);
        $ttl = config('services.meta.ad_library.cache_ttl_seconds', 3600);

        $cached = $this->cacheService->get($cacheKey);
        if ($cached !== null) {
            return ApiResponse::success($cached);
        }

        try {
            $client = MetaAdLibraryClient::forToken($token);
            $result = $client->searchAds($params);
            $this->cacheService->put($cacheKey, $result, $ttl);

            return ApiResponse::success($result);
        } catch (\RuntimeException $e) {
            $message = $e->getMessage();
            if (str_contains($message, '613')) {
                return ApiResponse::error('Rate limit exceeded. Please try again later.', null, 429);
            }
            if (str_contains($message, '190')) {
                return ApiResponse::error('Meta token expired. Please reconnect Meta in Connectors.', null, 401);
            }

            return ApiResponse::error($message, null, 500);
        }
    }

    public function searchesIndex(Request $request): JsonResponse
    {
        $searches = $request->user()
            ->adLibrarySearches()
            ->orderByDesc('updated_at')
            ->get()
            ->map(fn (AdLibrarySearch $s) => [
                'id' => $s->id,
                'query' => $s->query,
                'countries' => $s->countries,
                'ad_active_status' => $s->ad_active_status,
                'last_run_at' => $s->last_run_at?->toIso8601String(),
                'created_at' => $s->created_at->toIso8601String(),
            ]);

        return ApiResponse::success($searches);
    }

    public function searchesStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'query' => ['required', 'string', 'max:100'],
            'countries' => ['required', 'array'],
            'countries.*' => ['string', 'size:2'],
            'ad_active_status' => ['nullable', 'string', 'in:ACTIVE,INACTIVE,ALL'],
            'business_id' => ['nullable', 'exists:business_accounts,id'],
        ]);

        $search = $request->user()->adLibrarySearches()->create([
            'query' => $validated['query'],
            'countries' => $validated['countries'],
            'ad_active_status' => $validated['ad_active_status'] ?? 'ACTIVE',
            'business_account_id' => $validated['business_id'] ?? null,
        ]);

        return ApiResponse::success([
            'id' => $search->id,
            'query' => $search->query,
            'countries' => $search->countries,
            'ad_active_status' => $search->ad_active_status,
        ], 'Search saved.', null, 201);
    }

    public function searchesDestroy(Request $request, int $id): JsonResponse
    {
        $search = AdLibrarySearch::where('user_id', $request->user()->id)->findOrFail($id);
        $search->delete();

        return ApiResponse::success(null, 'Search deleted.');
    }

    public function collectionsIndex(Request $request): JsonResponse
    {
        $collections = $request->user()
            ->adLibraryCollections()
            ->withCount('items')
            ->orderByDesc('updated_at')
            ->get()
            ->map(fn (AdLibraryCollection $c) => [
                'id' => $c->id,
                'name' => $c->name,
                'items_count' => $c->items_count,
                'created_at' => $c->created_at->toIso8601String(),
            ]);

        return ApiResponse::success($collections);
    }

    public function collectionsStore(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $collection = $request->user()->adLibraryCollections()->create([
            'name' => $validated['name'],
        ]);

        return ApiResponse::success([
            'id' => $collection->id,
            'name' => $collection->name,
        ], 'Collection created.', null, 201);
    }

    public function collectionsShowItems(Request $request, AdLibraryCollection $collection): JsonResponse
    {
        if ($collection->user_id !== $request->user()->id) {
            abort(404);
        }

        $items = $collection->items()->orderByDesc('created_at')->get()->map(fn (AdLibraryCollectionItem $i) => [
            'id' => $i->id,
            'ad_archive_id' => $i->ad_archive_id,
            'snapshot_url' => $i->snapshot_url,
            'page_name' => $i->page_name,
            'ad_creative_body' => $i->ad_creative_body,
            'publisher_platforms' => $i->publisher_platforms,
            'ad_delivery_start_time' => $i->ad_delivery_start_time,
        ]);

        return ApiResponse::success($items);
    }

    public function collectionsAddItem(Request $request, AdLibraryCollection $collection): JsonResponse
    {
        if ($collection->user_id !== $request->user()->id) {
            abort(404);
        }

        $validated = $request->validate([
            'ad_archive_id' => ['required', 'string'],
            'snapshot_url' => ['nullable', 'string', 'url'],
            'page_name' => ['nullable', 'string'],
            'ad_creative_body' => ['nullable', 'string'],
            'page_id' => ['nullable', 'string'],
            'publisher_platforms' => ['nullable', 'array'],
            'ad_delivery_start_time' => ['nullable', 'string'],
        ]);

        $item = $collection->items()->firstOrCreate(
            ['ad_archive_id' => $validated['ad_archive_id']],
            [
                'snapshot_url' => $validated['snapshot_url'] ?? null,
                'page_name' => $validated['page_name'] ?? null,
                'ad_creative_body' => $validated['ad_creative_body'] ?? null,
                'page_id' => $validated['page_id'] ?? null,
                'publisher_platforms' => $validated['publisher_platforms'] ?? null,
                'ad_delivery_start_time' => $validated['ad_delivery_start_time'] ?? null,
            ]
        );

        return ApiResponse::success([
            'id' => $item->id,
            'ad_archive_id' => $item->ad_archive_id,
        ], 'Item added.', null, 201);
    }

    public function collectionsRemoveItem(Request $request, AdLibraryCollection $collection, int $item): JsonResponse
    {
        if ($collection->user_id !== $request->user()->id) {
            abort(404);
        }

        $itemModel = $collection->items()->findOrFail($item);
        $itemModel->delete();

        return ApiResponse::success(null, 'Item removed.');
    }

    private function getAllowedCountries(): array
    {
        $common = ['US', 'BD', 'GB', 'CA', 'AU', 'DE', 'FR', 'IN', 'BR', 'MX'];

        return $common;
    }
}
