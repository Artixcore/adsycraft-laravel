<?php

use App\Jobs\PublishDuePostsJob;
use App\Jobs\RefreshMetaTokensJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::job(new PublishDuePostsJob)->everyTenMinutes();
Schedule::job(new RefreshMetaTokensJob)->dailyAt('02:00');
Schedule::command('content:generate-daily')->hourly();
