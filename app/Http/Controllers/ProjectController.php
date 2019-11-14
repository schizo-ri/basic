<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectEmployee;


class ProjectController extends Controller
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
        $projects = Project::orderBy('project_no','ASC')->get();

        return view('Centaur::projects.index',['projects' => $projects]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Centaur::projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Project::where('project_no',$request['project_no'])->first()) {
            session()->flash('error', "Projekt sa tim brojem već postoji");
		
            return redirect()->back();
        } else {
            $data = array(
                'name' => $request['name'],
                'project_no'  => $request['project_no'],
                'start_date'  => $request['start_date'],
                'duration'  => $request['duration'],
                'day_hours'  => $request['day_hours'],
                'saturday'  => $request['saturday'],
            );
    
            $project = new Project();
            $project->saveProject($data);
            
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
        $project = Project::find($id);
        $projectEmployees = ProjectEmployee::where('project_id', $project->id)->get()->unique('employee_id');

        return view('Centaur::projects.show',['project' => $project,'projectEmployees' => $projectEmployees]);
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

        return view('Centaur::projects.edit',['project' => $project]);
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

        $data = array(
			'name' => $request['name'],
			'project_no'  => $request['project_no'],
			'start_date'  => $request['start_date'],
			'duration'  => $request['duration'],
			'day_hours'  => $request['day_hours'],
			'saturday'  => $request['saturday'],
        );

        $project->updateProject($data);
        
        session()->flash('success', "Podaci su spremljeni");
		
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
}
