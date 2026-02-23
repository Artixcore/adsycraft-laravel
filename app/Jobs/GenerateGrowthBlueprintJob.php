<?php

namespace App\Jobs;

use App\Models\BusinessAccount;
use App\Models\GrowthBlueprint;
use App\Services\AdLibrary\AdLibraryTokenResolver;
use App\Services\AI\Agents\OrchestratorAgent;
use App\Services\Meta\MetaAdLibraryClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateGrowthBlueprintJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 2;

    public array $backoff = [60, 300];

    public function __construct(
        public int $businessAccountId
    ) {}

    public function handle(OrchestratorAgent $orchestrator, AdLibraryTokenResolver $tokenResolver): void
    {
        $business = BusinessAccount::find($this->businessAccountId);
        if (! $business) {
            return;
        }

        $blueprint = GrowthBlueprint::create([
            'business_account_id' => $business->id,
            'status' => GrowthBlueprint::STATUS_DRAFT,
            'payload' => [],
            'error_message' => null,
        ]);

        try {
            $adLibraryData = $this->fetchAdLibraryData($business, $tokenResolver);
            $webSearchResults = ''; // Placeholder: integrate WebSearchService when available

            $payload = $orchestrator->run($business, [
                'ad_library_data' => $adLibraryData,
                'web_search_results' => $webSearchResults,
            ]);

            $blueprint->update([
                'status' => GrowthBlueprint::STATUS_COMPLETED,
                'payload' => $payload,
                'error_message' => null,
            ]);
        } catch (\Throwable $e) {
            Log::error('GenerateGrowthBlueprintJob failed', [
                'business_account_id' => $business->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $blueprint->update([
                'status' => GrowthBlueprint::STATUS_FAILED,
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchAdLibraryData(BusinessAccount $business, AdLibraryTokenResolver $tokenResolver): array
    {
        $user = $business->user;
        if (! $user || ! $tokenResolver->hasToken($user)) {
            return [];
        }

        $token = $tokenResolver->resolveForUser($user);
        if (! $token) {
            return [];
        }

        $pageIds = $business->competitorUrls()
            ->whereNotNull('meta_page_id')
            ->pluck('meta_page_id')
            ->unique()
            ->values()
            ->toArray();

        $searchTerms = $business->niche ?? $business->name ?? '';
        if (empty($pageIds) && empty(trim($searchTerms))) {
            return [];
        }

        $params = [
            'ad_reached_countries' => json_encode($business->settings['target_locations'] ?? ['US']),
            'ad_active_status' => 'ACTIVE',
        ];

        if (! empty($pageIds)) {
            $params['search_page_ids'] = json_encode($pageIds);
        } else {
            $params['search_terms'] = $searchTerms;
        }

        try {
            $client = MetaAdLibraryClient::forToken($token);
            $result = $client->searchAds($params);

            return $result['data'] ?? [];
        } catch (\Throwable $e) {
            Log::warning('Ad Library fetch failed in GenerateGrowthBlueprintJob', ['error' => $e->getMessage()]);

            return [];
        }
    }
}
