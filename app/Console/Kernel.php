<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
protected function scheduleTimezone()
{
    return 'America/La_Paz';
}

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
    protected function schedule(Schedule $schedule)
{
$schedule->command('app:enviar-notas-whats-app')->dailyAt('22:00');
//$schedule->command('app:enviar-notas-whats-app')->everyMinute();
}

}
