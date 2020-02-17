<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EmployeeRequest;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Project;
use App\Models\CategoryEmployee;
use App\Models\ProjectEmployee;
use DateTime;
use DatePeriod;
use DateInterval;

class EmployeeController extends Controller
{
    public function __construct()
    {
        // Middleware
        $this->middleware('sentinel.auth');
    }
        
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::orderBy('last_name','ASC')->get();
        $projEmp = ProjectEmployee::get();

        return view('Centaur::employees.index', ['employees' => $employees, 'projEmp' => $projEmp]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = CategoryEmployee::orderBy('mark','ASC')->get();
        
        return view('Centaur::employees.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeRequest $request)
    {
        if(Employee::where('first_name',trim($request['first_name']))->where('last_name',trim($request['last_name']))->first() ) {
            session()->flash('error', "Djelatnik sa tim imenom i prezimenom već postoji");
		
            return redirect()->back();
        } else {
            $data = array(
                'first_name' => trim($request['first_name']),
                'last_name'  => trim($request['last_name']),
                'category_id'  => trim($request['category_id'])
            );
           
            $employee = new Employee();
            $employee->saveEmployee($data);
            
            session()->flash('success', "Podaci su spremljeni");
            
            return redirect()->back();
        }
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
        $employee = Employee::find($id);
        $categories = CategoryEmployee::orderBy('mark','ASC')->get();

        return view('Centaur::employees.edit',['employee' => $employee, 'categories' => $categories]);
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
        $employee = Employee::find($id);

        $data = array(
			'first_name' => trim($request['first_name']),
            'last_name'  => trim($request['last_name']),
            'category_id'  => $request['category_id']
        );

        $employee->updateEmployee($data);

        session()->flash('success', "Podaci su ispravljeni");
		
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
        $employee = Employee::find($id);
        if( $employee) {
            $employee->delete();

            $project_employees = ProjectEmployee::where('employee_id', $id)->get();
            $projects_id = array();
            $employees_id = array();

            if( $project_employees) {
                foreach ($project_employees->unique('project_id') as $project_employee) {
                    array_push( $projects_id, $project_employee->project_id );
                }
                foreach ($project_employees as $project_employee) {
                    $project_employee->delete();
                }
                foreach ($projects_id as $project_id) {
                    $project = Project::where('id',  $project_id)->first();
                    
                    $project_employees = ProjectEmployee::where('project_id', $project->id)->get();
                    foreach ($project_employees->unique('employee_id') as $project_employee) {
                        array_push( $employees_id, $project_employee->employee_id );
                        $project_employee->delete();
                    }
                    
                    if( isset($employees_id) && count($employees_id) > 0) {
                        $count_employees = count($employees_id);  // 1 djelatnika na projektu
                        
                        $interval = DateInterval::createFromDateString('1 day');
                        $begin = new DateTime($project->start_date);
                        
                        $project_duration =  $project->duration; //100
                        $project_day_hours = $project->day_hours; // 9- sati u danu
                        
                        $days =   $project_duration / $project_day_hours; // 12 - trajanje dana           
                        $calc_days = intval( $days /  $count_employees); 
                        
                        if( ($project_duration % $project_day_hours) || ($days %  $count_employees) ) {
                            $calc_days ++;
                        }
                    
                        $date = new DateTime($project->start_date);
                        
                        for ($i=0; $i < $calc_days; $i++) {
                            if( $project->saturday == 0 ) { 
                                if( date_format($date,'N') <= 5) {
                                    foreach ( $employees_id as $employee_id) {
                                        $data = array(
                                            'project_id'    => $project_id,
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
                                            'project_id'    =>  $project_id,
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
                }
            }
            $message = session()->flash('success',  "Djelatnik je obrisan");                    
            return redirect()->back()->withFlashMessage($message);
        } else {
            $message = session()->flash('error',  "Djelatnik nije nađen");                    
            return redirect()->back()->withFlashMessage($message);
        }
    }

}