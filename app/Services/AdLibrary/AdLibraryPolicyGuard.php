<?php

namespace App\Services\AdLibrary;

class AdLibraryPolicyGuard
{
    public function validateSearchParams(array $params): bool
    {
        $countries = $params['countries'] ?? $params['ad_reached_countries'] ?? [];
        if (empty($countries)) {
            return false;
        }

        $hasSearch = ! empty($params['query'] ?? $params['search_terms'] ?? null)
            || ! empty($params['search_page_ids'] ?? null);

        return $hasSearch;
    }

    public function getDisclaimerText(): string
    {
        return 'Data from Meta Ad Library. For competitive research and inspiration only. Subject to Meta\'s Terms of Service and Ad Library API policies.';
    }

    /**
     * @return list<string>
     */
    public function getAllowedFields(): array
    {
        return [
            'id',
            'ad_archive_id',
            'ad_snapshot_url',
            'page_id',
            'page_name',
            'ad_creative_body',
            'ad_creative_bodies',
            'publisher_platforms',
            'ad_delivery_start_time',
            'ad_delivery_stop_time',
        ];
    }
}
