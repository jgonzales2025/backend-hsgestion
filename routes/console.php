<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

\Illuminate\Support\Facades\Schedule::command('exchange:update')->dailyAt('09:15');

\Illuminate\Support\Facades\Schedule::call(function () {
    $directories = ['purchases', 'pdf'];
    foreach ($directories as $directory) {
        $files = \Illuminate\Support\Facades\Storage::disk('public')->files($directory);
        foreach ($files as $file) {
            $lastModified = \Illuminate\Support\Facades\Storage::disk('public')->lastModified($file);
            // 24 hours in seconds: 24 * 60 * 60 = 86400
            if (time() - $lastModified > 86400) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($file);
            }
        }
    }
})->hourly();
