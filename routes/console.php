<?php

use App\Console\Commands\AttachmentClear;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Custom command to clear unattached files
Schedule::command(AttachmentClear::class)->daily('15:10');
