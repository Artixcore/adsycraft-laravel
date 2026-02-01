<?php

namespace App\Jobs;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class AdsOptimizerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $campaigns = Campaign::query()->where('status', 'ACTIVE')->get();

        foreach ($campaigns as $campaign) {
            try {
                Log::info('AdsOptimizerJob: would optimize campaign', ['campaign_id' => $campaign->id]);
            } catch (\Throwable $e) {
                Log::error('AdsOptimizerJob: failed', ['campaign_id' => $campaign->id, 'error' => $e->getMessage()]);
            }
        }
    }
}
