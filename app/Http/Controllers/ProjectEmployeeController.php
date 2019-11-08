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
        //
    }

    public function save ($employee_id, $date, $project_id)
    {
        $project = Project::where('project_no', $project_id)->first();
        $project_employees = ProjectEmployee::where('project_id', $project->project_no)->where('employee_id', $employee_id)->get();

        if($project_employees && count($project_employees) > 0 ) {
            session()->flash('error', "Zaposlenik je već zadužen na projektu");
            return redirect()->back();
        } else {
            $begin = new DateTime($project->start_date);
            $end =  new DateTime($project->start_date);

            $broj_dana = $project->duration / $project->day_hours;
            $end->modify('+' .  $broj_dana . ' day');
            
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
            foreach ($period as $dan) {
                    
                    $data = array(
                        'project_id'    => $project_id,
                        'employee_id'   => $employee_id,
                        'date'          => $dan
                    );
                
                
                $project_employee = new ProjectEmployee();
                $project_employee->saveProjectEmployee($data);
            }
           
           return redirect()->back();

        }
    }

}
