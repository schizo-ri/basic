<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Project;
use App\Models\ProjectEmployee;


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
            $project_duration = $project->duration / $project->day_hours;  //8 dana
            $count = count(ProjectEmployee::where('project_id', $project->project_no)->get()->unique('employee_id'));
           
            $title = $project->project_no . ' | ' . $count . ' ljudi dodijeljeno';
           
            $days = 5;
            $weekdays = 2;
            if( $project->saturday == 1) {
                  $weekdays = 1;
            }

            for ($i=0; $i <= $project_duration ; $i++) {
                if($i == 0) {
                    $start_date =  $project->start_date;
                    
                    switch (date( 'N',strtotime( $start_date)) ) {
                        case 1:
                            $days = 5;
                            break;
                        case 2:
                            $days = 4;
                            break;
                        case 3:
                            $days = 3;
                            break;
                        case 4:
                            $days = 2;
                            break;
                        case 5:
                            $days = 1;
                            break;
                        default:
                            $days = 5;
                            break;
                    }
                    if( $project->saturday == 1) {
                        $days++;
                    }
                    if ($days > $project_duration) {
                        $days = $project_duration;
                    } 

                    $end_date = date('Y-m-d',strtotime("+" .   $days . " day", strtotime($start_date)));
                    $project_duration = $project_duration - $days;
                   
                } else {
                    
                    $start_date =  date('Y-m-d',strtotime("+" .   $weekdays . " day", strtotime($end_date) ));
                    
                    switch (date( 'N',strtotime( $start_date)) ) {
                        case 1:
                            $days = 5;
                            break;
                        case 2:
                            $days = 4;
                            break;
                        case 3:
                            $days = 3;
                            break;
                        case 4:
                            $days = 2;
                            break;
                        case 5:
                            $days = 1;
                            break;
                        default:
                            $days = 5;
                            break;
                    }
                    if( $project->saturday == 1) {
                        $days++;
                    }
                   
                    if ($days > $project_duration) {
                        $days = $project_duration;
                    } 
                    $end_date = date('Y-m-d',strtotime("+" .   $days . " day", strtotime($start_date)));
                    
                    $project_duration = $project_duration - $days;       
                }
                $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                
                array_push($dataArr, ['id'=>$project->project_no ,'title' => $title , 'start' => $start_date, 'end' => $end_date, 'classNames' => $project->project_no]);
            }
        }

        return view('Centaur::dashboard',['employees' => $employees, 'project_employees' => $project_employees, 'dataArr' => $dataArr]);
    }
}
