<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Project;
use App\Models\ProjectEmployee;
use DateTime;
use DatePeriod;
use DateInterval;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(isset($request) && isset($request['date'])) {
            $project_employees  = ProjectEmployee::where('date', $request['date'])->get();           
        } else {
            $project_employees  = ProjectEmployee::get();
        }
    
        $employees = Employee::orderBy('employees.last_name','ASC')->get();
        $projects = Project::get();
        $dataArr = array();

        foreach($projects as $project) {
            $count_people = 0;
            $unique_people = array();
            $projEmpls  = ProjectEmployee::where('project_id', $project->id)->get();
            if($projEmpls->count() > 0) {
                $project_duration = $projEmpls->unique('date')->count(); // broji unique dane po projektu u project_employees
               /* $people_onProject = $projEmpls->unique('employee_id')->count();
               dd( $project_duration);
                $people_onProject = array();
                foreach ($projEmpls as $projEmpl) {
                    foreach ($projEmpls->unique('date') as $unique_date) {
                        if($projEmpl->value('date') ==  $unique_date->date) {
                            $count_people ++;
                            array_push($unique_people, $projEmpl->employee_id);
                        }
                    }
                    
                }*/
            } else {
                $project_duration = $project->duration / $project->day_hours;  //11 dana              
            }

           
           
            if( $project->saturday == 1) {
                $weekdays = 1;
                $days = 6;
            } else {
                $weekdays = 2;
                $days = 5;
            }

            for ($i=0; $i <= $project_duration ; $i++) {
                if($i == 0) {
                    $start_date =  $project->start_date;
                    
                    switch (date( 'N',strtotime( $start_date)) ) {
                        case 1:
                            $days = $days;
                            break;
                        case 2:
                            $days = $days-1;
                            break;
                        case 3:
                            $days = $days-2;
                            break;
                        case 4:
                            $days = $days-3;
                            break;
                        case 5:
                            $days = $days-4;
                            break;
                        case 5:
                            $days = $days-5;
                            break;
                        case 6:
                            $days = $days-6;
                            break;
                    }
                    if($days == -1) {
                        $days= 5;
                    }
                    if ($days > $project_duration) {
                        $days = $project_duration;
                    } 

                    $end_date = date('Y-m-d',strtotime("+" .   $days . " day", strtotime($start_date)));   // *******************
                    $project_duration = $project_duration - $days;
                   
                } else {
                    if( $project->saturday == 1) {
                        $weekdays = 1;
                        $days = 6;
                    } else {
                        $weekdays = 2;
                        $days = 5;
                    }
                    $start_date =  date('Y-m-d',strtotime("+" .   $weekdays . " day", strtotime($end_date) ));
                  
                    switch (date( 'N',strtotime( $start_date)) ) {
                        case 1:
                            $days = $days;
                            break;
                        case 2:
                            $days = $days-1;
                            break;
                        case 3:
                            $days = $days-2;
                            break;
                        case 4:
                            $days = $days-3;
                            break;
                        case 5:
                            $days = $days-4;
                            break;
                        case 5:
                            $days = $days-5;
                            break;
                        case 6:
                            $days = $days-6;
                            break;
                    }
                    if($days == -1) {
                        $days= 5;
                    }

                    if ($days > $project_duration) {
                        $days = $project_duration;
                    } 
                   
                    $end_date = date('Y-m-d',strtotime("+" .   $days . " day", strtotime($start_date)));  // *******************
                    
                    $project_duration = $project_duration - $days;       
                }
                $url = "http://$_SERVER[HTTP_HOST]" . "/projects/" . $project->id;
                $count = count(ProjectEmployee::where('project_id', $project->id)->get()->unique('employee_id'));

                $title = $project->project_no . ' | ' . $count . ' ljudi dodijeljeno';
                array_push($dataArr, ['id'=>$project->id ,'title' => $title , 'start' => $start_date, 'end' => $end_date, 'classNames' => $project->project_no, 'url' => $url]);
            }
        }
        //http://localhost:8000/projects/7/edit
        return view('Centaur::dashboard',['employees' => $employees, 'projects' => $projects, 'project_employees' => $project_employees, 'dataArr' => $dataArr]);
    }
}
