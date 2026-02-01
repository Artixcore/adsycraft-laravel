<?php

namespace App\Jobs;

use App\Models\MetaAsset;
use App\Models\PageInsight;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FetchPageInsightsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $assets = MetaAsset::query()
            ->whereNotNull('page_id')
            ->whereNotNull('page_access_token')
            ->with('businessAccount')
            ->get();

        foreach ($assets as $asset) {
            try {
                $this->fetchForAsset($asset);
            } catch (\Throwable $e) {
                Log::error('FetchPageInsightsJob: failed for asset', [
                    'meta_asset_id' => $asset->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    private function fetchForAsset(MetaAsset $asset): void
    {
        $periodDate = now()->toDateString();
        $metrics = ['page_impressions' => 0, 'page_reach' => 0];

        PageInsight::updateOrCreate(
            [
                'meta_asset_id' => $asset->id,
                'period' => 'day',
                'period_date' => $periodDate,
            ],
            [
                'business_account_id' => $asset->business_account_id,
                'metrics' => $metrics,
            ]
        );

        Log::info('FetchPageInsightsJob: stored stub insights', ['meta_asset_id' => $asset->id]);
    }
}
