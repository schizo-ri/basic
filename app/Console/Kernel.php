<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Employee_absence::class,
        \App\Console\Commands\CampaignEmails::class,
        \App\Console\Commands\NoticeSchedule::class,
        \App\Console\Commands\ClearDatabase::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('email:absence_day')
                 /*  ->dailyAt('8:00') */
                  ->everyMinute()
                  ->evenInMaintenanceMode();
        $schedule->command('email:campaign')
                   ->everyMinute()
                   ->evenInMaintenanceMode();
        $schedule->command('notice')
                  ->everyMinute()
                  ->evenInMaintenanceMode();
        /* $schedule->command('command:clear_database')
                  ->dailyAt('00:00')
				  ->evenInMaintenanceMode(); */
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
