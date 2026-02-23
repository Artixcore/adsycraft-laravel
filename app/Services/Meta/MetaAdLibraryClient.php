<?php

namespace App\Services\Meta;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetaAdLibraryClient
{
    private const FIELDS = 'id,ad_snapshot_url,page_id,page_name,ad_creative_bodies,publisher_platforms,ad_delivery_start_time,ad_delivery_stop_time';

    public function __construct(
        private readonly string $accessToken,
        private readonly string $graphVersion,
        private readonly int $rateLimitBackoff = 2
    ) {}

    public static function forToken(string $accessToken): self
    {
        return new self(
            $accessToken,
            config('services.meta.graph_version', 'v21.0'),
            (int) config('services.meta.ad_library.rate_limit_backoff', 2)
        );
    }

    /**
     * @return array{data: array<int, array>, paging: array{next_cursor?: string, has_more: bool}}
     */
    public function searchAds(array $params): array
    {
        if (config('services.meta.stub', false)) {
            return $this->stubResponse();
        }

        $apiParams = $this->mapToApiParams($params);
        $url = "https://graph.facebook.com/{$this->graphVersion}/ads_archive";

        $attempt = 0;
        $maxAttempts = 4;

        while (true) {
            $attempt++;
            $start = microtime(true);

            $response = Http::withToken($this->accessToken)->get($url, array_merge($apiParams, [
                'fields' => self::FIELDS,
            ]));

            $durationMs = (int) ((microtime(true) - $start) * 1000);

            if ($response->successful()) {
                $body = $response->json();
                Log::info('Ad Library API request succeeded', [
                    'duration_ms' => $durationMs,
                    'result_count' => count($body['data'] ?? []),
                ]);

                return $this->normalizeResponse($body);
            }

            $errorBody = $response->json();
            $errorCode = $errorBody['error']['code'] ?? 0;
            $errorMessage = $errorBody['error']['message'] ?? 'Unknown error';

            Log::warning('Ad Library API request failed', [
                'duration_ms' => $durationMs,
                'error_code' => $errorCode,
                'error_message' => $errorMessage,
            ]);

            if ($errorCode === 613 && $attempt < $maxAttempts) {
                $sleepSeconds = $this->rateLimitBackoff ** $attempt;
                sleep($sleepSeconds);

                continue;
            }

            throw new \RuntimeException("Ad Library API error: [{$errorCode}] {$errorMessage}");
        }
    }

    /**
     * @return array{data: array<int, array>, paging: array{next_cursor?: string, has_more: bool}}
     */
    public function searchAdsWithCursor(array $params, ?string $afterCursor): array
    {
        if ($afterCursor) {
            $params['after'] = $afterCursor;
        }

        return $this->searchAds($params);
    }

    private function mapToApiParams(array $params): array
    {
        $api = [];

        if (! empty($params['search_terms'])) {
            $api['search_terms'] = $params['search_terms'];
        }
        if (! empty($params['search_page_ids'])) {
            $api['search_page_ids'] = is_array($params['search_page_ids'])
                ? json_encode($params['search_page_ids'])
                : $params['search_page_ids'];
        }
        if (! empty($params['ad_reached_countries'])) {
            $countries = $params['ad_reached_countries'];
            $api['ad_reached_countries'] = is_array($countries) ? json_encode($countries) : $countries;
        }
        if (! empty($params['ad_active_status'])) {
            $api['ad_active_status'] = $params['ad_active_status'];
        }
        if (! empty($params['ad_delivery_date_min'])) {
            $api['ad_delivery_date_min'] = $params['ad_delivery_date_min'];
        }
        if (! empty($params['ad_delivery_date_max'])) {
            $api['ad_delivery_date_max'] = $params['ad_delivery_date_max'];
        }
        if (! empty($params['media_type'])) {
            $api['media_type'] = $params['media_type'];
        }
        if (! empty($params['publisher_platforms'])) {
            $platforms = $params['publisher_platforms'];
            $api['publisher_platforms'] = is_array($platforms) ? json_encode($platforms) : $platforms;
        }
        if (! empty($params['ad_type'])) {
            $api['ad_type'] = $params['ad_type'];
        }
        if (! empty($params['after'])) {
            $api['after'] = $params['after'];
        }

        return $api;
    }

    /**
     * @return array{data: array<int, array>, paging: array{next_cursor?: string, has_more: bool}}
     */
    private function normalizeResponse(array $body): array
    {
        $data = $body['data'] ?? [];
        $paging = $body['paging'] ?? [];
        $cursors = $paging['cursors'] ?? [];

        $normalized = [];
        foreach ($data as $ad) {
            $bodies = $ad['ad_creative_bodies'] ?? [];
            $normalized[] = [
                'id' => $ad['id'] ?? null,
                'ad_archive_id' => $ad['id'] ?? null,
                'ad_snapshot_url' => $ad['ad_snapshot_url'] ?? null,
                'page_id' => $ad['page_id'] ?? null,
                'page_name' => $ad['page_name'] ?? null,
                'ad_creative_bodies' => $bodies,
                'ad_creative_body' => is_array($bodies) ? (reset($bodies) ?: null) : $bodies,
                'publisher_platforms' => $ad['publisher_platforms'] ?? [],
                'ad_delivery_start_time' => $ad['ad_delivery_start_time'] ?? null,
                'ad_delivery_stop_time' => $ad['ad_delivery_stop_time'] ?? null,
            ];
        }

        return [
            'data' => $normalized,
            'paging' => [
                'next_cursor' => $cursors['after'] ?? null,
                'has_more' => isset($paging['next']),
            ],
        ];
    }

    /**
     * @return array{data: array<int, array>, paging: array{next_cursor?: string, has_more: bool}}
     */
    private function stubResponse(): array
    {
        return [
            'data' => [
                [
                    'id' => 'stub_ad_1',
                    'ad_archive_id' => 'stub_ad_1',
                    'ad_snapshot_url' => 'https://www.facebook.com/ads/library/?id=stub_ad_1',
                    'page_id' => 'stub_page_1',
                    'page_name' => 'Stub Page',
                    'ad_creative_bodies' => ['Stub ad creative text for testing.'],
                    'ad_creative_body' => 'Stub ad creative text for testing.',
                    'publisher_platforms' => ['facebook', 'instagram'],
                    'ad_delivery_start_time' => '2024-01-01T00:00:00+0000',
                    'ad_delivery_stop_time' => null,
                ],
            ],
            'paging' => [
                'next_cursor' => null,
                'has_more' => false,
            ],
        ];
    }
}
