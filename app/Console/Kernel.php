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
        \App\Console\Commands\EquipmentListUpdate::class,
        \App\Console\Commands\PreparationCreate::class,
        \App\Console\Commands\PreparationUpdate::class,
        \App\Console\Commands\DesigningScheduleCommand::class,
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
        $schedule->command('command:listUpdate')
                  ->dailyAt('11:00')
                  ->evenInMaintenanceMode();
        $schedule->command('command:listUpdate')
                  ->dailyAt('15:00')
                  ->evenInMaintenanceMode();
        $schedule->command('command:listUpdate')
                  ->everyMinute()
                  ->evenInMaintenanceMode();
        $schedule->command('command:preparation_create')
                  ->dailyAt('12:00')
                  ->evenInMaintenanceMode();
        $schedule->command('command:preparation_update')
                    ->dailyAt('14:30')
                  ->evenInMaintenanceMode();
        $schedule->command('schedule')
                  /* ->everyMinute() */
                  ->dailyAt('06:30')
                ->evenInMaintenanceMode();
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
