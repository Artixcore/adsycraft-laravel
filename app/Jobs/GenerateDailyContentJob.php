<?php

namespace App\Jobs;

use App\Models\BusinessAccount;
use App\Models\Post;
use App\Services\AI\StubCaptionGenerator;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GenerateDailyContentJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [60, 300, 900];

    public int $maxExceptions = 2;

    public function __construct(
        public int $businessAccountId,
        public ?string $date = null
    ) {}

    public function uniqueId(): string
    {
        $date = $this->date ?? Carbon::today()->toDateString();

        return "generate_daily_content:{$this->businessAccountId}:{$date}";
    }

    public function handle(): void
    {
        $business = BusinessAccount::find($this->businessAccountId);
        if (! $business || ! $business->autopilot_enabled) {
            return;
        }

        $date = $this->date
            ? Carbon::parse($this->date, $business->timezone)
            : Carbon::now($business->timezone)->startOfDay();

        $cacheKey = "generate_daily_content:{$this->businessAccountId}:{$date->toDateString()}";
        if (Cache::has($cacheKey)) {
            return;
        }

        Cache::put($cacheKey, true, now()->addDay());

        $count = (int) ($business->posts_per_day ?? 1);
        $count = max(1, min(20, $count));

        $captions = null;
        if ($business->aiConnections()->where('is_primary', true)->where('is_enabled', true)->exists()) {
            $captions = app(StubCaptionGenerator::class)->generate($business, $count, $date->toDateString());
        }

        for ($i = 0; $i < $count; $i++) {
            $scheduledAt = $date->copy()->setHour(8)->setMinute(0)->setSecond(0)->addHours($i * 3);
            $caption = $captions[$i] ?? 'Generated post placeholder for ' . $scheduledAt->toDateTimeString();

            Post::create([
                'business_account_id' => $business->id,
                'channel' => 'facebook',
                'caption' => $caption,
                'media_type' => null,
                'media_prompt' => null,
                'scheduled_at' => $scheduledAt,
                'status' => Post::STATUS_SCHEDULED,
            ]);
        }

        Log::info('GenerateDailyContentJob: generated posts', [
            'business_account_id' => $this->businessAccountId,
            'date' => $date->toDateString(),
            'count' => $count,
        ]);
    }
}
