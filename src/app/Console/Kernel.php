<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //勤務時間を２２時に//
        $schedule->command('auto:attendance start')->dailyAt('9:30');

        //休憩時間を23時に//
        $schedule->command('auto:attendance breakStart')->dailyAt('10:00');

        //休憩終了を2時に//
        $schedule->command('auto:attendance breakEnd')->dailyAt('10:30');

        //勤務終了時間を6:00に//
        $schedule->command('auto:attendance end')->dailyAt('12:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
