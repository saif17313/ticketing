<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule booking expiry check every hour
Schedule::command('bookings:expire')->hourly();

// Release expired bookings (payment deadline passed) - run every minute
Schedule::command('bookings:release-expired')->everyMinute();

// Unlock seats with expired locks - run every minute
Schedule::command('seats:unlock-expired')->everyMinute();
