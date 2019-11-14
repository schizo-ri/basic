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
        
            if( $project->saturday == 1) {
                $weekdays = 1;
                $days = 6;
            } else {
                $weekdays = 2;
                $days = 5;
            }
            if($projEmpls->count() > 0) {
                $project_duration = $projEmpls->unique('date')->count(); // broji unique dane po projektu u project_employees
                $counter = intval(round($project_duration/$days,0,PHP_ROUND_HALF_DOWN));
                if($project_duration % $days) {
                    $counter ++;
                }
            } else {
                $project_duration = $project->duration / $project->day_hours;  //16 dana    
                $counter =   intval(round($project_duration/$days,0,PHP_ROUND_HALF_DOWN));
                if($project_duration % $days) {
                    $counter ++;
                }
            }
           
            for ($i=0; $i < $counter; $i++) {
                if( $project_duration > 0) {
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
                            if( $project->saturday == 1) {
                                $days = 6;
                            } else {
                                $days = 5;
                            }
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
                            if( $project->saturday == 1) {
                                $days = 6;
                            } else {
                                $days = 5;
                            }
                        }
                       
                        if($project_duration <  $days) {
                       
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
        }
        //http://localhost:8000/projects/7/edit
        return view('Centaur::dashboard',['employees' => $employees, 'projects' => $projects, 'project_employees' => $project_employees, 'dataArr' => $dataArr]);
    }
}
