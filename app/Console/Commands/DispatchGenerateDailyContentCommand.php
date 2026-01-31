<?php

namespace App\Console\Commands;

use App\Jobs\GenerateDailyContentJob;
use App\Models\BusinessAccount;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DispatchGenerateDailyContentCommand extends Command
{
    protected $signature = 'content:generate-daily';

    protected $description = 'Dispatch GenerateDailyContentJob for businesses where local time is 06:00';

    public function handle(): int
    {
        $businesses = BusinessAccount::query()
            ->where('autopilot_enabled', true)
            ->get();

        $dispatched = 0;
        foreach ($businesses as $business) {
            $localNow = Carbon::now($business->timezone);
            if ($localNow->format('H:i') !== '06:00') {
                continue;
            }

            $today = $localNow->toDateString();
            GenerateDailyContentJob::dispatch($business->id, $today);
            $dispatched++;
            $this->info("Dispatched GenerateDailyContentJob for business {$business->id} ({$today}).");
        }

        if ($dispatched === 0) {
            $this->comment('No businesses due for 06:00 content generation.');
        }

        return self::SUCCESS;
    }
}
