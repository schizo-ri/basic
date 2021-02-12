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
        \App\Console\Commands\CheckOut::class,
        \App\Console\Commands\CarsRegistration::class,
        \App\Console\Commands\AnniversaryCommand::class,
        \App\Console\Commands\MedicalExaminationCommand::class,
        \App\Console\Commands\TerminationCommand::class,
        \App\Console\Commands\EmployeeTrainingCommand::class,
        \App\Console\Commands\ProbationCommand::class,
        \App\Console\Commands\BirthdayCommand::class,
        \App\Console\Commands\StrangerCommand::class,
        \App\Console\Commands\TaskCreateNotification::class,
        \App\Console\Commands\IDCardExpired::class,
        \App\Console\Commands\SickLeaveCommand::class,
        \App\Console\Commands\DiaryCommand::class,
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
       
       /*  $schedule->command('email:absence_day') 
                ->dailyAt('12:00')  
                //->everyMinute()
                ->evenInMaintenanceMode();
        $schedule->command('email:campaign')
                ->dailyAt('7:00') 
                ->evenInMaintenanceMode();
        $schedule->command('notice')
                ->dailyAt('7:00') 
                ->evenInMaintenanceMode(); 
        $schedule->command('check_out')
                ->dailyAt('7:00') 
                ->evenInMaintenanceMode(); 
        $schedule->command('anniversary')
                // ->everyMinute()
                ->dailyAt('7:00') 
                ->evenInMaintenanceMode();
        $schedule->command('medicalExamination')
                ->dailyAt('7:00') 
                ->evenInMaintenanceMode(); 
         $schedule->command('checkout_employee')
                ->dailyAt('15:00') 
                ->evenInMaintenanceMode(); 
         $schedule->command('employeeTraining')
                ->dailyAt('7:00') 
                ->evenInMaintenanceMode(); 
         $schedule->command('probation')
                ->dailyAt('7:00') 
                ->evenInMaintenanceMode(); 
        $schedule->command('stranger')
                ->dailyAt('7:00') 
                ->evenInMaintenanceMode();
        $schedule->command('task')
                ->everyMinute()
                ->evenInMaintenanceMode();  
        $schedule->command('car_registration')
                ->dailyAt('7:00') 
                // ->everyMinute()
                ->evenInMaintenanceMode();
        $schedule->command('birthday')
                ->dailyAt('7:00') 
                // ->everyMinute()
                ->evenInMaintenanceMode();
        $schedule->command('idCard')
                // ->everyMinute()
                ->dailyAt('7:00') 
                ->evenInMaintenanceMode();
        
        $schedule->command('diary')
                // ->everyMinute()
                ->dailyAt('20:00') 
                ->evenInMaintenanceMode(); 
        $schedule->command('sickLeave')
                 ->everyMinute()
                //->dailyAt('12:00') 
                ->evenInMaintenanceMode(); */

        $schedule->command('task')
                ->everyMinute()
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
