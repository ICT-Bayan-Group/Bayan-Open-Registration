<?php

namespace App\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Scheduling\Schedule;

class Kernel extends ConsoleKernel
{
    protected array $commands = [
        Commands\SendPaymentReminders::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('whatsapp:send-reminders')->hourly();
    }
}
