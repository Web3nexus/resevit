<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\SmartTenantsMigrate;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Explicitly register SmartTenantsMigrate to ensure availability
Artisan::starting(function ($artisan) {
    $artisan->resolve(SmartTenantsMigrate::class);
});

use Illuminate\Support\Facades\Schedule;

Schedule::command('system:pulse')->everyMinute();
