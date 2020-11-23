<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectEmployee;
use App\Models\Employee;
use App\Models\CategoryEmployee;
use App\Http\Controllers\ProjectEmployeeController;
use DateTime;
use DatePeriod;
use DateInterval;

class ProjectController extends Controller
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
        $projects = Project::orderBy('project_no','ASC')->get();
        $categoryEmp = CategoryEmployee::orderBy('mark','ASC')->get();
        $projEmp = ProjectEmployee::get();

        return view('Centaur::projects.index',['projects' => $projects, 'categoryEmp' => $categoryEmp, 'projEmp' => $projEmp]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = CategoryEmployee::get();

        return view('Centaur::projects.create',['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      /*   if(Project::where('project_no',$request['project_no'])->first()) {
            session()->flash('error', "Projekt sa tim brojem već postoji");
		
            return redirect()->back();
        } else { */
            $categories = '';
            if($request['categories']) {
                $categories = implode(',',$request['categories']);
            }           

            $data = array(
                'name' => $request['name'],
                'project_no'  => $request['project_no'],
                'start_date'  => $request['start_date'],
                'duration'  => $request['duration'],
                'day_hours'  => $request['day_hours'],
                'saturday'  => $request['saturday'],
                'categories'  =>  $categories,
            );
            
            if ($request['end_date'] != null) {
                $data += ['end_date'  => $request['end_date']];
            } else {
                $data += ['end_date'  => null];
            }

            $project = new Project();
            $project->saveProject($data);
            
            session()->flash('success', "Podaci su spremljeni");
            
            return redirect()->back();
        }
 //   }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::find($id);
        $projectEmployees = ProjectEmployee::where('project_id', $project->id)->get()->unique('employee_id');
        $employees = Employee::orderBy('first_name')->get();
        $categories = CategoryEmployee::orderBy('mark')->get();

        return view('Centaur::projects.show',['project' => $project,'employees' => $employees,'projectEmployees' => $projectEmployees, 'categories' => $categories]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::find($id);
        $categories = CategoryEmployee::get();

        return view('Centaur::projects.edit',['project' => $project, 'categories' => $categories]);
    }


    public function updateProject(Request $request)
    {
        $project = Project::find($request['id']);
      
        if(isset($request['name'])) {
            $data = array('name' => $request['name']);
        } else if ($request['start_date'])  {
            $data = array('start_date' => $request['start_date']);
        } else if ($request['end_date'])  {
            $data = ['end_date' => $request['end_date']];
        } else if ($request['duration'])  {
            $data = ['duration' => $request['duration']];
        }
  
        $project->updateProject($data);
        
        return "sve ok";
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
        $project = Project::find($id); 
        $categories = null;

        if($request['categories']) {
            $categories = implode(',',$request['categories']);
        }       

        $data = array(
			'name' => $request['name'],
			'project_no'  => $request['project_no'],
			'start_date'  => $request['start_date'],
			'duration'  => $request['duration'],
			'day_hours'  => $request['day_hours'],
            'saturday'  => $request['saturday'],
            'categories'  => $categories
        );

        if ($request['end_date'] != null) {
            $data += ['end_date'  => $request['end_date']];
        } else {
            $data += ['end_date'  => null];
        }

        $project->updateProject($data);

        if( isset($request['employee_id']) && count($request['employee_id']) > 0 ) {
            ProjectEmployeeController::store( $request );
        } else {
            ProjectEmployeeController::uskladi($project->id);
        }
      
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
        $project = Project::find($id);
        $project->delete();

        $project_employees = ProjectEmployee::where('project_id',$project->id)->get();
        foreach ($project_employees as $project_employee) {
            $project_employee->delete();
        }
        
		$message = session()->flash('success',  "Projekt je obrisan");
		
		return redirect()->back()->withFlashMessage($message);
    }

    public function url_project_update( $id, $date)
    {
        $project = Project::find($id);

        $data = array(
			'start_date'  => date("Y-m-d",strtotime($date))
        );

        $project->updateProject($data);

		return $date;
       // return redirect()->back();
    }

    public function close_project ($id) 
    {
        $project = Project::find($id);
        if ($project->active == 1) {
            $active = 0;
        } else {
            $active = 1;
        }

        $data = array(
			'active' => $active		
        );

        $project->updateProject($data);
        if ($project->active == 0) {
            session()->flash('success', "Podaci su spremljeni, projekt je neaktivan.");
        } else {
            session()->flash('success', "Podaci su spremljeni, projekt je aktivan.");
        }
        return redirect()->back();
    }
}
