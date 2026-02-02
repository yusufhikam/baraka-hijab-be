<?php

use App\Console\Commands\CleanupExpiredTransactions;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Schedule::command(CleanupExpiredTransactions::class)
        ->everyFiveMinutes()
        ->withoutOverlapping()
        ->sendOutputTo(storage_path('logs/cron-'.date('Y-m-d').'.log'));

// Untuk testing local: uncomment baris ini
// Schedule::command(CleanupExpiredTransactions::class)->everyMinute();