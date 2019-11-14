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
    public function index($project_id)
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
    public function store(Request $request)
    {
        $data = array(
			'project_id'    =>  $request['project_id'],
			'employee_id'   => $request['employee_id'],
			'date'          => $request['date']
        );
       
        $project_employee = new ProjectEmployee();
        $project_employee->saveProjectEmployee($data);
		
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

        $project_employee->delete();

        session()->flash('success', "Djelatnik je obrisan sa projekta za selektirani dan");
        return redirect()->back();

    }

    public function save ($employee_id, $date, $project_id, $all_days)
    {
        $project = Project::where('id', $project_id)->first();
        $projEmpls  = ProjectEmployee::where('project_id', $project->id)->get(); 
        $count_projEmpl  = $projEmpls->count(); // 11
        
        if( $all_days == 1) {
            $project_employees = ProjectEmployee::where('project_id', $project->project_no)->where('employee_id', $employee_id)->get();
            $modify_day = 0;

            if($project_employees && count($project_employees) > 0) {
                session()->flash('error', "Zaposlenik je već zadužen na projektu, ne može se spremiti na cijeli projekt. Pokušaj spremiti na određeni dan");
                return redirect()->back();
            } else {
                $begin = new DateTime($project->start_date);
                $end =  new DateTime($project->start_date);
                $broj_dana = $project->duration / $project->day_hours; 
                if ($count_projEmpl > 0) {
                    $broj_dana = $projEmpls->unique('date')->count();
                }
                $end->modify('+' .  $broj_dana . ' day');
               
               // $end->setTime(0,0,1);
                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($begin, $interval, $end);
                foreach ($period as $dan) {
                    if(date_format($dan,'N') == 7 || (date_format($dan,'N') == 6 && $project->saturday == 0 ) ) {
                        $modify_day++;
                    }
                }
                $end->modify('+' .  $modify_day . ' day');

                $period = new DatePeriod($begin, $interval, $end);
                foreach ($period as $dan) {
                    if(date_format($dan,'N') != 7 && date_format($dan,'N') != 6 ) {
                        $data = array(
                            'project_id'    => $project_id,
                            'employee_id'   => $employee_id,
                            'date'          => $dan
                        );
                        $project_employee = new ProjectEmployee();
                        $project_employee->saveProjectEmployee($data);
                    }  else if( date_format($dan,'N') == 6 && $project->saturday == 1 ) {
                        $data = array(
                            'project_id'    => $project_id,
                            'employee_id'   => $employee_id,
                            'date'          => $dan
                        );
                        $project_employee = new ProjectEmployee();
                        $project_employee->saveProjectEmployee($data);
                    } 
                }
            }
            
        } else {
            $project_employees = ProjectEmployee::where('project_id', $project->project_no)->where('employee_id', $employee_id)->where('date', $date)->first();
            if($project_employees) {
                session()->flash('error', "Zaposlenik je već zadužen na projektu");
                return redirect()->back();
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
    
       return redirect()->back();
    }

    public static function brisi( $project_id ) 
    {
        $project = Project::where('id', $project_id)->first();

        $projEmpls  = ProjectEmployee::where('project_id', $project->id)->get(); 
        $count_projEmpl  = $projEmpls->count(); // 11
        
        $project_duration = round($project->duration / $project->day_hours,0,PHP_ROUND_HALF_UP);  //11 dana
        if($count_projEmpl > 0) {
            $project_duration = $projEmpls->unique('date')->count() / $projEmpls->unique('employee_id')->count();
            if($projEmpls->unique('date')->count() % $projEmpls->unique('employee_id')->count()) {
                $project_duration++;
            }
        }
        
        $modify_day = 0;
        $begin = new DateTime($project->start_date);
        $end =  new DateTime($project->start_date);
       
        $end->modify('+' .  $project_duration . ' day');
    //    $end->setTime(0,0,1);
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        foreach ($period as $dan) {
            if(date_format($dan,'N') == 7 ) {
                $modify_day++;
            } else if (date_format($dan,'N') == 6 && $project->saturday == 0 ) {
                $modify_day++;
            }
        }
       
        $end->modify('+' .  $modify_day . ' day');
       
        foreach ($projEmpls as $projEmpl) {
            if($projEmpl->date >= date_format($end,'Y-m-d')) {
                $projEmpl->delete();
            }
        }
        return date_format($end,'Y-m-d');
        return redirect()->back();
    }

}
