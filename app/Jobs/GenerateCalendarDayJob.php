<?php

namespace App\Jobs;

use App\Models\BusinessAccount;
use App\Models\Post;
use App\Services\AI\AIManager;
use App\Services\AI\StubCaptionGenerator;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateCalendarDayJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $businessAccountId,
        public string $date,
        public ?int $count = null
    ) {}

    public function handle(): void
    {
        $business = BusinessAccount::find($this->businessAccountId);
        if (! $business) {
            return;
        }

        $date = Carbon::parse($this->date, $business->timezone ?? 'UTC');
        $targetCount = $this->count ?? max(1, (int) ($business->posts_per_day ?? 1));
        $targetCount = max(1, min(20, $targetCount));

        $existingCount = $business->posts()
            ->whereDate('scheduled_at', $date)
            ->count();

        if ($existingCount >= $targetCount) {
            return;
        }

        $toGenerate = $targetCount - $existingCount;
        $captions = null;

        if (app(AIManager::class)->hasConfiguredProvider()) {
            $captions = app(StubCaptionGenerator::class)->generate($business, $toGenerate, $date->toDateString());
        }

        $existingTimes = $business->posts()
            ->whereDate('scheduled_at', $date)
            ->pluck('scheduled_at')
            ->map(fn ($t) => Carbon::parse($t)->format('H:i'))
            ->toArray();

        $baseHour = 8;
        $interval = 3;
        $generated = 0;

        for ($i = 0; $i < 20 && $generated < $toGenerate; $i++) {
            $scheduledAt = $date->copy()->setHour($baseHour)->setMinute(0)->setSecond(0)->addHours($i * $interval);
            $timeStr = $scheduledAt->format('H:i');
            if (in_array($timeStr, $existingTimes, true)) {
                continue;
            }
            $existingTimes[] = $timeStr;

            $caption = $captions[$generated] ?? 'Generated post placeholder for '.$scheduledAt->toDateTimeString();

            Post::create([
                'business_account_id' => $business->id,
                'channel' => 'facebook',
                'caption' => $caption,
                'media_type' => null,
                'media_prompt' => null,
                'scheduled_at' => $scheduledAt,
                'status' => Post::STATUS_SCHEDULED,
            ]);
            $generated++;
        }
    }
}
