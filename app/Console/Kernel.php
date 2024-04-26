<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;


class Kernel extends ConsoleKernel
{
    protected $commands = [
    \App\Console\Commands\SaveDealsToDB::class,
    ];
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Schedule the task to run every minute
        /* $schedule->command('app:save-deals-to-d-b')->everyMinute();
        $schedule->command('app:save-contacts-to-d-b')->everyMinute();
        $schedule->command('app:save-tasks-to-d-b')->everyMinute();
        $schedule->command('app:save-notes-to-d-b')->everyMinute();
        $schedule->command('app:save-contact-group-in-d-b')->everyMinute(); */
        $schedule->command('app:save-group-in-d-b')->everyMinute();
        $schedule->command('app:save-module-to-d-b')->everyMinute();
        $schedule->command('app:composite-api')->everyMinute();
        $schedule->command('app:save-a-c-i-in-d-b')->everyMinute();
        // $schedule->command('app:save-attachment-to-d-b')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
