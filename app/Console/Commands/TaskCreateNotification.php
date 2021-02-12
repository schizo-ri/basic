<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DateTime;
use App\Models\Task;
use App\Models\EmployeeTask;
use App\Models\Employee;
use App\Http\Controllers\BasicAbsenceController;
use App\Mail\TaskCreateMail;
use Illuminate\Support\Facades\Mail;
use Log;

class TaskCreateNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Task Notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today = new DateTime('now');
        Log::info('TaskCreateNotification');
        
        $tasks = Task::whereDate('end_date', '>=', date('Y-m-d'))->where('active', 1)->get();

        foreach ($tasks as $task) {
            $task_date =  new DateTime( $task->start_date );
            $end_date = null;
            if($task->end_date != null ) {
                $end_date =  new DateTime($task->end_date);
                $end_date->setTime(0,0,1);
            }
            
            // switch period
            switch ( $task->interval ) {
                case 'no_repeat':                       
                    $task_date =  $task_date;
                    break;
                case 'every_day':
                    $task_date = $today;
                    break;
                case 'once_week':                        
                    if(date_format($today, 'N') == date_format($task_date, 'N')) {
                        $task_date = $today;
                    }                
                    break;
                case 'once_month':
                    if(date_format($today, 'd') == date_format($task_date, 'd')) {
                        $task_date = $today;
                    }
                    break;
                case 'once_year':
                    if(date_format($today, 'd') == date_format($task_date, 'd') && date_format($today, 'm') == date_format($task_date, 'm')) {
                        $task_date = $today;
                    }                     
                    break;
                default:
                    $task_date =  $task_date;
            }

            // ako je vikend pomakni na ponedjeljak
            if(date_format($task_date, 'N') == 6 ) {
                $end_date->modify('+2days');
            } else if(date_format($task_date, 'N') == 7 ) {
                $end_date->modify('+1day');
            } 

            if( $end_date == null || strtotime(date_format($task_date, 'Y-m-d')) <= strtotime(date_format($end_date, 'Y-m-d'))) {
                if( date_format($task_date, 'Y-m-d') == date_format($today, 'Y-m-d') ) {
                    $employee_ids = explode(',', $task->to_employee_id);

                    // smjena svih zaduženih, svaki put slijedeći,  ako ima izostanak!!!
                    /* $empl_id = $employee_ids[0];
                    $response = true;
                    if(count( $employee_ids ) > 1) {
                        $prev_task = EmployeeTask::where('task_id', $task->id)->orderBy('created_at','DESC')->first();
                        if($prev_task) {
                            $key = array_search( $prev_task->employee_id, $employee_ids);
                            if ( $key == (count( $employee_ids ) -1) ) {
                                $key = 0;
                            } else {
                                $key = $key+1;
                            }
                            $empl_id = $employee_ids[$key];

                            $response = BasicAbsenceController::absenceForDayTask($empl_id, date_format($task_date, 'Y-m-d'));  // djelatnik ima zahtjev na dan (a nije Izlazak)
                            if( $response == false ) {
                                if ( $key == (count( $employee_ids ) -1) ) {
                                    $key = 0;
                                } else {
                                    $key = $key+1;
                                }
                                $empl_id = $employee_ids[$key];
                            }
                        }
                    }  */
                    foreach ($employee_ids as $key => $empl_id) {
                        $employee =  Employee::where('id', $empl_id )->first();

                        if( $task->energy_consumptions == 1 ) {
                            $user = Sentinel::findById( $employee->user_id );
                            $role = Sentinel::findRoleBySlug('energenti');
                          
                            if( ! $user->inRole('energenti') ) {
                                $role->users()->attach($user);
                            }
                        }
    
                        $emails = array($employee->email, 'jelena.juras@duplico.hr');
                        
                        Log::info('TaskCreateNotification: ');
                        Log::info($emails);
                        $data = array(
                            'task_id'  	    => $task->id,
                            'employee_id'  	=>  $empl_id  
                        );                
                        
                        $employeeTask = new EmployeeTask();
                        $employeeTask->saveEmployeeTask( $data);                     
                        foreach ($emails as $email) {
                            if( $email != null && $email != '') {
                                Mail::to($email)->send(new TaskCreateMail($employeeTask));
                            }   
                        }
                    }
                }
            }
        }
    }
}