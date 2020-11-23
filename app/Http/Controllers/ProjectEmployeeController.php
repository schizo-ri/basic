<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectEmployee;
use App\Models\Project;
use DateTime;
use DatePeriod;
use DateInterval;

class ProjectEmployeeController extends Controller
{
    /**
	*
	* Set middleware to quard controller.
	* @return void
	*/
	public function __construct()
	{
		$this->middleware('sentinel.auth');
    }
        
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $project_employees  = ProjectEmployee::join('employees','project_employees.employee_id','employees.id')->select('project_employees.*','employees.*')->orderBy('employees.last_name','ASC')->get();

        return $project_employees;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function store(Request $request)
    {
        $project = Project::where('id',  $request['project_id'])->first();
        $project_employees = ProjectEmployee::where('project_id', $project->id)->delete();       

        if(! $request['employee_id']) {
            session()->flash('error', "Nedovoljno podataka za snimanje. Nisu uneseni djelatnici.");
            return redirect()->back();
        } else if(! $project->day_hours || ! $project->duration ) {
            session()->flash('error', "Nedovoljno podataka za snimanje. Nisu uneseni svi podaci na projektu.");
            return redirect()->back();
        } else {
            if( isset($request['employee_id']) && $request['employee_id'] ) {
                if(is_array($request['employee_id'])) {   // snima za sve dane djelatnika
                    $count_employees = count($request['employee_id']); // 2 djelatnika na projektu
                    $interval = DateInterval::createFromDateString('1 day');
                    $begin = new DateTime($project->start_date);
                 
                    $project_duration =  $project->duration; //120
                    $project_day_hours = $project->day_hours; // 10- sati u danu
                    
                    $days =   $project_duration / $project_day_hours; // 12 - trajanje dana           
                    $calc_days = intval( $days /  $count_employees); 
                   
                    if( ($project_duration % $project_day_hours) || ($days %  $count_employees) ) {
                        $calc_days ++;
                    }
                 
                    $date = new DateTime($project->start_date);
                  
                    for ($i=0; $i < $calc_days; $i++) {
                        if( $project->saturday == 0 ) { 
                            if( date_format($date,'N') <= 5) {
                                foreach ( $request['employee_id'] as $employee_id) {
                                    $data = array(
                                        'project_id'    => $request['project_id'],
                                        'employee_id'   => $employee_id,
                                        'date'          => date_format($date,'Y-m-d') 
                                    );
                                    
                                    $project_employee = new ProjectEmployee();
                                    $project_employee->saveProjectEmployee($data);
                                }
                                $date->modify('+1 day');
                            }  else if (date_format($date,'N') == 7 || date_format($date,'N') == 6) {
                                $date->modify('+1 day');
                                $i--;
                            }   
                        } else {
                            if( date_format($date,'N') <= 6) {
                                foreach ( $request['employee_id'] as $employee_id) {
                                    $data = array(
                                        'project_id'    => $request['project_id'],
                                        'employee_id'   => $employee_id,
                                        'date'          => date_format($date,'Y-m-d') 
                                    );
                                    
                                    $project_employee = new ProjectEmployee();
                                    $project_employee->saveProjectEmployee($data);
                                }
                                $date->modify('+1 day');
                            } else if (date_format($date,'N') == 7 ) {
                                $date->modify('+1 day');
                                $i--;
                            }                    
                        }
                    }
        
                } else { // snima za određeni dan određenog djelatnika
                    if(isset($request['date'])) {
                        $data = array(
                            'project_id'    => $request['project_id'],
                            'employee_id'   => $request['employee_id'],
                            'date'          => $request['date']
                        );
                       
                        $project_employee = new ProjectEmployee();
                        $project_employee->saveProjectEmployee($data);
                    } else {
                        session()->flash('error', "Nedovoljno podataka za snimanje.");
                        return redirect()->back();
                    }
                }
            }
        }
        
        session()->flash('success', "Djelatnici su upisani na projekt");
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $project_employee = ProjectEmployee::find($id);

        $data = array(
			'project_id'   => $request['project_id'],
			'employee_id'  => $request['employee_id'],
			'date'         => $request['date']
        );
       
        $project_employee->updateProjectEmployee($data);
		
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project_employee = ProjectEmployee::find($id);       
        $project_id = $project_employee->project_id;
        $employee_id =  $project_employee->employee_id;
        $project_employees = ProjectEmployee::where('project_id', $project_id)->where('employee_id', $employee_id)->get();

        foreach ($project_employees as $project_empl) {
            $project_empl->delete();
        }       

        ProjectEmployeeController::uskladi($project_id);

        session()->flash('success', "Djelatnik je obrisan sa projekta");
        return redirect()->back();
    }

    public function save ($employee_id, $date, $project_id, $all_days)
    {
        $project = Project::where('id', $project_id)->first();
        $projEmpls  = ProjectEmployee::where('project_id', $project->id)->get(); 
        
        if( $all_days == 1) {
            $project_employees = ProjectEmployee::where('project_id', $project_id)->where('employee_id', $employee_id)->get();

            if($project_employees && count($project_employees) > 0) {
                session()->flash('error', "Zaposlenik je već zadužen na projektu, ne može se spremiti na cijeli projekt. Pokušaj spremiti na određeni dan");
                return null;
            } else {
                $interval = DateInterval::createFromDateString('1 day');
                $begin = new DateTime($project->start_date);
                $project_duration =  $project->duration; //120
                $project_day_hours = $project->day_hours; // 10- sati u danu
                $days =   $project_duration / $project_day_hours; // 12 - trajanje dana     
                $count_employees = count(ProjectEmployee::where('project_id', $project->id)->get()->unique('employee_id')); // 3      
                if( $count_employees == 0 ) {
                    $count_employees = 1;
                }
                $calc_days = intval( $days /  $count_employees); 
 
                if( ($project_duration % $project_day_hours) || ($days %  $count_employees) ) {
                    $calc_days ++;
                }
                $date = new DateTime($project->start_date);

                for ($i=0; $i < $calc_days; $i++) {
                    if( $project->saturday == 0 ) { 
                        if( date_format($date,'N') <= 5) {
                            $data = array(
                                'project_id'    => $project->id,
                                'employee_id'   => $employee_id,
                                'date'          => date_format($date,'Y-m-d') 
                            );
                            $project_employee = new ProjectEmployee();
                            $project_employee->saveProjectEmployee($data);
                           
                            $date->modify('+1 day');
                        }  else if (date_format($date,'N') == 7 || date_format($date,'N') == 6) {
                            $date->modify('+1 day');
                            $i--;
                        }   
                    } else {
                        if( date_format($date,'N') <= 6) {
                        
                            $data = array(
                                'project_id'    => $project->id,
                                'employee_id'   => $employee_id,
                                'date'          => date_format($date,'Y-m-d') 
                            );
                            $project_employee = new ProjectEmployee();
                            $project_employee->saveProjectEmployee($data);
                          
                            $date->modify('+1 day');
                        } else if (date_format($date,'N') == 7 ) {
                            $date->modify('+1 day');
                            $i--;
                        }                    
                    }
                }          
            }
        } else {
            $project_employees = ProjectEmployee::where('project_id', $project_id)->where('employee_id', $employee_id)->where('date', $date)->first();
            if($project_employees) {
                session()->flash('error', "Zaposlenik je već zadužen na projektu za taj dan");
                return 1;
            } else {
                $data = array(
                    'project_id'    => $project_id,
                    'employee_id'   => $employee_id,
                    'date'          => $date
                );
                $project_employee = new ProjectEmployee();
                $project_employee->saveProjectEmployee($data);
            }
        }
    
     //  return redirect()->back();
       return "ok";
    }

    public static function brisi( $project_id ) 
    {
        $project = Project::where('id', $project_id)->first();
        $projEmpls  = ProjectEmployee::where('project_id', $project->id)->get(); 
        $count_projEmpl  = $projEmpls->count(); //  132
        $project_duration = intval(round($project->duration / $project->day_hours,0,PHP_ROUND_HALF_DOWN));  //66 dana
        $broj_djelatnika_na_projektu = $projEmpls->unique('employee_id')->count(); //2
      //  dd($broj_djelatnika_na_projektu);
        if($count_projEmpl > 0) {
            $project_duration =  
            intval(round($project_duration /  $projEmpls->unique('employee_id')->count(),0,PHP_ROUND_HALF_DOWN));
            if($project_duration % $count_projEmpl) {
                $project_duration ++;
            }
        }
      // dd($project_duration);
        $begin = new DateTime($project->start_date);
        $end =  new DateTime($project->start_date);
      //  $end->setTime(0,0,1);
     
        $end->modify('+' .  $project_duration . ' day');
     
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

      //  dd ( date_format($end,'Y-m-d'));

        foreach ($period as $dan) {
            if((date_format($dan,'N') == 7) || ( date_format($dan,'N') == 6 && $project->saturday == 0)) {
                $end->modify('+1 day');
            } 
        }

        if((count($projEmpls)>0)) {
            foreach ($projEmpls as $projEmpl) {
                if($projEmpl->date >= date_format($end,'Y-m-d')) {
                    $projEmpl->delete();
                }
            }
        }
      // dd( $period);
       if( date_format($end,'N') == 7) {
            $end->modify('+1 day');
        }
    /* 
         //    
        $second_begin = $end;
     
        $period2 = new DatePeriod($second_begin, $interval, $end);
        foreach ($period2 as $dan) {   
            if((date_format($dan,'N') == 7) ||  ( date_format($dan,'N') == 6 && $project->saturday == 0)) {
                $end->modify('+1 day');
            } 
        }
      */
   //    return redirect()->back();

      return null;
    }

    public static function uskladi( $project_id ) 
    {
        $project = Project::find($project_id );
        $employees = ProjectEmployee::where('project_id', $project->id)->get()->unique('employee_id'); // 3
        $employees_id = array();

        foreach ($employees as $employee) {
           array_push($employees_id,$employee->employee_id );   //skupi sve id od djelatnika
        }
        $count_employees = count($employees_id); // 3
        if($count_employees > 0) {
            $project_employees_delete = ProjectEmployee::where('project_id', $project->id)->delete();

            $interval = DateInterval::createFromDateString('1 day');
            $begin = new DateTime($project->start_date);
         
            $project_duration =  $project->duration; //120
            $project_day_hours = $project->day_hours; // 10- sati u danu
            $days =   $project_duration / $project_day_hours; // 12 - trajanje dana
            if($count_employees ==0 ) {
                $count_employees = 1;
            }
            $calc_days = intval( $days /  $count_employees);  
            if( ($project_duration % $project_day_hours) || ($project_duration % $project_day_hours) || ($days %  $count_employees) ) {
                $calc_days ++;
            }
         
            $date = new DateTime($project->start_date);
            for ($i=0; $i < $calc_days; $i++) {
                if( $project->saturday == 0 ) { 
                    if( date_format($date,'N') <= 5) {
                        foreach ($employees_id as $employee_id) {
                            $data = array(
                                'project_id'    => $project->id,
                                'employee_id'   => $employee_id,
                                'date'          => date_format($date,'Y-m-d') 
                            );
                            
                            $project_employee = new ProjectEmployee();
                            $project_employee->saveProjectEmployee($data);
                        }
                        $date->modify('+1 day');
                    }  else if (date_format($date,'N') == 7 || date_format($date,'N') == 6) {
                        $date->modify('+1 day');
                        $i--;
                    }   
                } else {
                    if( date_format($date,'N') <= 6) {
                        foreach ( $employees_id as $employee_id) {
                            $data = array(
                                'project_id'    => $project->id,
                                'employee_id'   => $employee_id,
                                'date'          => date_format($date,'Y-m-d') 
                            );
                            
                            $project_employee = new ProjectEmployee();
                            $project_employee->saveProjectEmployee($data);
                        }
                        $date->modify('+1 day');
                    } else if (date_format($date,'N') == 7 ) {
                        $date->modify('+1 day');
                        $i--;
                    }                    
                }
            }
        }
        return null;
    }
}
