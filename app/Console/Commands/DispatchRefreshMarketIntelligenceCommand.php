<?php

namespace App\Console\Commands;

use App\Jobs\RefreshMarketIntelligenceJob;
use App\Models\BusinessAccount;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DispatchRefreshMarketIntelligenceCommand extends Command
{
    protected $signature = 'market-intelligence:refresh-weekly';

    protected $description = 'Dispatch RefreshMarketIntelligenceJob for businesses where local time is Monday 00:00';

    public function handle(): int
    {
        $businesses = BusinessAccount::query()->get();

        $dispatched = 0;
        foreach ($businesses as $business) {
            $localNow = Carbon::now($business->timezone ?? 'UTC');
            if ($localNow->dayOfWeek !== Carbon::MONDAY || $localNow->format('H:i') !== '00:00') {
                continue;
            }

            RefreshMarketIntelligenceJob::dispatch($business->id);
            $dispatched++;
            $this->info("Dispatched RefreshMarketIntelligenceJob for business {$business->id}.");
        }

        if ($dispatched === 0) {
            $this->comment('No businesses due for weekly market intelligence refresh.');
        }

        return self::SUCCESS;
    }
}
