<?php

namespace App\Jobs;

use App\Models\MetaAsset;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RefreshMetaTokensJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [60, 300, 900];

    public int $maxExceptions = 2;

    public function handle(): void
    {
        $nearExpiry = Carbon::now()->addDays(7);
        $assets = MetaAsset::query()
            ->whereNotNull('token_expires_at')
            ->where('token_expires_at', '<=', $nearExpiry)
            ->get();

        foreach ($assets as $asset) {
            try {
                // TODO: Call Meta Graph API to exchange short-lived for long-lived token.
                // For V1 stub: just log and optionally bump expires_at.
                Log::info('RefreshMetaTokensJob: would refresh token', [
                    'meta_asset_id' => $asset->id,
                    'token_expires_at' => $asset->token_expires_at?->toIso8601String(),
                ]);
            } catch (\Throwable $e) {
                Log::error('RefreshMetaTokensJob: failed to refresh token', [
                    'meta_asset_id' => $asset->id,
                    'error' => $e->getMessage(),
                ]);
                throw $e;
            }
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('RefreshMetaTokensJob: job failed', [
            'exception' => $exception->getMessage(),
        ]);
    }
}
