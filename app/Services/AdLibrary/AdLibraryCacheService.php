<?php

namespace App\Services\AdLibrary;

use Illuminate\Support\Facades\Cache;

class AdLibraryCacheService
{
    private const PREFIX = 'ad_library:search:';

    public function get(string $cacheKey): ?array
    {
        $key = self::PREFIX.$cacheKey;
        $cached = Cache::get($key);

        if ($cached === null) {
            return null;
        }

        return is_array($cached) ? $cached : null;
    }

    public function put(string $cacheKey, array $payload, int $ttlSeconds): void
    {
        $key = self::PREFIX.$cacheKey;
        Cache::put($key, $payload, $ttlSeconds);
    }

    public function forget(string $cacheKey): bool
    {
        return Cache::forget(self::PREFIX.$cacheKey);
    }

    public static function buildCacheKey(array $params): string
    {
        $normalized = [
            'query' => $params['query'] ?? $params['search_terms'] ?? '',
            'countries' => $params['countries'] ?? $params['ad_reached_countries'] ?? [],
            'ad_active_status' => $params['ad_active_status'] ?? 'ACTIVE',
            'started_after' => $params['started_after'] ?? $params['ad_delivery_date_min'] ?? null,
            'started_before' => $params['started_before'] ?? $params['ad_delivery_date_max'] ?? null,
            'search_page_ids' => $params['search_page_ids'] ?? [],
            'media_type' => $params['media_type'] ?? null,
            'platform' => $params['platform'] ?? $params['publisher_platforms'] ?? null,
            'ad_type' => $params['ad_type'] ?? null,
            'after' => $params['after'] ?? null,
        ];

        return hash('sha256', json_encode($normalized));
    }
}
