<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Project;
use App\Models\ProjectEmployee;
use App\Models\CategoryEmployee;
use App\Models\Publish;
use App\Models\PublishProject;
use Sentinel;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Sentinel::check()) {
            if(isset($request) && isset($request['date'])) {
                $project_employees = ProjectEmployee::where('date', $request['date'])->get();
                $project_employees = $project_employees->merge(ProjectEmployee::where('date', date('Y-m-d', strtotime('+1 day', strtotime($request['date']))))->get());
                $projects = collect();
                $projects_empl = $project_employees->unique('project_id');     
                foreach( $projects_empl as $proj ) {            
                    $projects = $projects->merge(Project::where('id', $proj->project_id)->get());
                }
            } else {
                $today = date('Y-m-d');
                $date = date_create($today);
                date_modify($date, '-1 month');
                
                $projects = Project::whereDate('end_date', '>', date_format($date, 'Y-m-d'))->get();
                $project_employees = collect();
                foreach( $projects as $project ) {            
                    $project_employees = $project_employees->merge(ProjectEmployee::where('project_id',$project->id)->get());
                }
            } 
            $categories = CategoryEmployee::orderBy('mark')->get();
            $employees = Employee::orderBy('employees.first_name','ASC')->get();
           
            $dataArr = array();
            $dataArrResource = array();
    
            foreach($projects->where('active',1) as $project) {

                $count_people = 0;
                $unique_people = array();
                $projEmpls  = ProjectEmployee::where('project_id', $project->id)->orderBy('date','DESC')->get();
                
                if( $project->saturday == 1) {
                    $weekdays = 1;
                    $days = 6;
                } else {
                    $weekdays = 2;
                    $days = 5;
                }
                
                if($projEmpls->count() > 0) {
                    $project_duration = $projEmpls->unique('date')->count(); // broji unique dane po projektu u project_employees
                   
                    if($project_duration % $days) {
                      //  $project_duration ++;
                    }
                } else {
                    $project_duration =intval($project->duration / $project->day_hours) ;  //12 dana    
                    
                    if($project->duration % $project->day_hours) {
                        $project_duration ++;
                    }
                }
             
                for ($i=0; $i <= $project_duration; $i++) {
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
                                    $days = $days-5;
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
                                    $days = $days-5;
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
                            $i--;                   
                        }
    
                        $url = "http://$_SERVER[HTTP_HOST]" . "/projects/" . $project->id;
                        $count = count(ProjectEmployee::where('project_id', $project->id)->get()->unique('employee_id'));
    
                        $proj_categories = '';
                        if($project->categories) {
                            $proj_categories = explode(',', $project->categories);
                        }
                        
                        $category_list = '';
                        if($proj_categories) {
                            foreach ($proj_categories as $category) {
                                $category_list .= $categories->where('id', $category )->first()->mark . ' ';  
                            }
                        }
                        if($count > 4) {
                            $lj = ' lj';
                        } else {
                            $lj = ' č';
                        }
                   
                        $title = $project->project_no . ' | ' . $project->name . ' | ' . $count .  $lj  . ' | ' . 'kat: ' .   $category_list;
                        $description = $project->name;
                        $classNames = $project->project_no; 

                        $resourceIds = '"' . 'Rok: ' . date('d.m.Y',strtotime($project->end_date )) .'",';
                        if($projEmpls->first()) {
                            $resourceIds .= '"' . 'Završetak izvođenja: ' . date('d.m.Y', strtotime($projEmpls->first()->date)) . ' ",';

                            if( $projEmpls->first()->date > $project->end_date ) {
                                $classNames .= " red_border";
                            }
                        }
                        $resourceIds .= '"' . '~~~~~~~~~~~~~~~~~~~~~~~~~' .'",';
                        foreach ($projEmpls->unique('employee_id') as $projEmpl) {
                            $employee =  $employees->where('id',$projEmpl->employee_id )->first();
                            $resourceIds .= '"' . $employee['first_name'] . ' ' . $employee['last_name'] .'",';
                        }
                      
                        array_push($dataArr, ['id'=>$project->id ,'title' => $title , 'description' => $description , 'start' => $start_date, 'end' => $end_date, 'classNames' => $classNames, 'url' => $url, 'resourceIds' => '[' . substr($resourceIds, 0, -1) . ']' ]);
                    }
                }
                
                foreach ($projEmpls as $projEmpl) {
                    array_push($dataArrResource, ['id'=> $projEmpl->id ,'title' => $projEmpl->employee['first_name'] . ' ' . $projEmpl->employee['last_name'] ]);     
                }
            }

            if(Sentinel::inRole('administrator')) {
                return view('Centaur::dashboard',['employees' => $employees, 'projects' => $projects, 'project_employees' => $project_employees, 'dataArr' => $dataArr, 'dataArrResource' => $dataArrResource, 'categories' => $categories]);
            } else {
                return redirect()->route('preparations.index');
            }
        } else {           
            return view('Centaur::auth.login');
        }        
    }

    public function live(Request $request)
    {
        if(isset($request) && isset($request['date'])) {
            $project_employees  = Publish::get();
            $projects = collect();
            $projects_empl = $project_employees->unique('project_id');     
            foreach( $projects_empl as $proj ) {            
                $projects = $projects->merge(PublishProject::where('project_id', $proj->project_id)->get());
            }
        } else {
            $project_employees  = Publish::get();
            $projects = PublishProject::get();
        }
        $categories = CategoryEmployee::orderBy('mark')->get();
        $employees = Employee::orderBy('employees.first_name','ASC')->get();
        
        $dataArr = array();

        foreach($projects as $project) {
            $count_people = 0;
            $unique_people = array();
            $projEmpls  = Publish::where('project_id', $project->project_id)->get();

            if( $project->saturday == 1) {
                $weekdays = 1;
                $days = 6;
            } else {
                $weekdays = 2;
                $days = 5;
            }
            
            if($projEmpls->count() > 0) {
                $project_duration = $projEmpls->unique('date')->count(); // broji unique dane po projektu u project_employees
                if($project_duration % $days) {
                    //  $project_duration ++;
                }
            } else {
                $project_duration =intval($project->duration / $project->day_hours) ;  //12 dana    
                
                if($project->duration % $project->day_hours) {
                    $project_duration ++;
                }
            }
            
            for ($i=0; $i <= $project_duration; $i++) {
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
                                $days = $days-5;
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
                    /*   
                        if(date('N',strtotime("+" .   $weekdays . " day", strtotime($end_date) )) == 7) {
                            $start_date =  date('Y-m-d',strtotime("+" .   ($weekdays + 1 ) . " day", strtotime($end_date) ));
                            $project_duration--;
                        } else {
                            $start_date =  date('Y-m-d',strtotime("+" .   $weekdays . " day", strtotime($end_date) ));
                        }
                        */  
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
                                $days = $days-5;
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
                        $i--;                   
                    }

                    $url = "http://$_SERVER[HTTP_HOST]" . "/projects/" . $project->project_id;
                    $count = count(Publish::where('project_id', $project->project_id)->get()->unique('employee_id'));

                    $proj_categories = '';
                    if($project->categories) {
                        $proj_categories = explode(',', $project->categories);
                    }
                    
                    $category_list = '';
                    if($proj_categories) {
                        foreach ($proj_categories as $category) {
                            $category_list .= $categories->where('id', $category )->first()->mark . ' ';  
                        }
                    }
                    if($count > 4) {
                        $lj = ' lj';
                    } else {
                        $lj = ' č';
                    }
                
                    $title = $project->project_no . ' | ' . $project->name . ' | ' . $count .  $lj  . ' | ' . 'kat: ' .   $category_list;
                    $description = $project->name;
                    $classNames = $project->project_no; 
                    if( date('Y-m-d',strtotime("-1 day", strtotime($end_date))) > $project->end_date) {
                        $classNames .= " red_border";
                    }
                    
                    array_push($dataArr, ['id'=>$project->project_id ,'title' => $title , 'description' => $description , 'start' => $start_date, 'end' => $end_date, 'classNames' => $classNames]);
                }
            }
        }
        
        return view('Centaur::live',['employees' => $employees, 'projects' => $projects, 'project_employees' => $project_employees, 'dataArr' => $dataArr, 'categories' => $categories]);
    }
}