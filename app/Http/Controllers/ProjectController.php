<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Models\Project;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\WorkTask;
use App\Models\WorkDiaryItem;
use App\Imports\ProjectsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Mail\ErrorMail;
use Illuminate\Support\Facades\Mail;
use Sentinel;

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
        $projects = Project::where('active',1)->get();
        $customers = Customer::get();

        $permission_dep = DashboardController::getDepartmentPermission();

		return view('Centaur::projects.index', ['projects' => $projects, 'customers' => $customers, 'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::orderBy('name','ASC')->get();
        $employees = Employee::employees_lastNameASC();

        return view('Centaur::projects.create',['customers' =>  $customers, 'employees' =>  $employees]);
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
			'name'  		=> $request['name'],
			'erp_id'  		=> $request['erp_id'],
			'customer_oib'  => $request['customer_oib'],
			'employee_id'  => $request['employee_id'],
			'employee_id2'  => $request['employee_id2'],
			'object'  		=> $request['object'],
			'active'        => $request['active'],
        );
        
		$project = new Project();
        $project->saveProject($data);
        
        session()->flash('success',  __('ctrl.data_save'));
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
        $project = Project::where('id', $id )->with('hasDiary')->with('hasProjectWorkTask')->first();
        $work_tasks = WorkTask::get();
        $projectWorkTasks = $project->hasProjectWorkTask;
		$diaries = $project->hasDiary->pluck('id')->toArray();
        $diary_items = WorkDiaryItem::whereIn('diary_id', $diaries)->get();
        
        return view('Centaur::projects.show',['project' =>  $project, 'work_tasks' => $work_tasks,'projectWorkTasks' =>  $projectWorkTasks, 'diary_items' =>  $diary_items] );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::find( $id );

        $customers = Customer::orderBy('name','ASC')->get();
        $employees = Employee::employees_lastNameASC();

        return view('Centaur::projects.edit',['project' =>  $project, 'customers' =>  $customers, 'employees' =>  $employees]);
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
        $project = Project::find( $id );

        $data = array(
			'name'  		=> $request['name'],
			'erp_id'  		=> $request['erp_id'],
			'customer_oib'  => $request['customer_oib'],
            'employee_id'  => $request['employee_id'],
			'employee_id2'  => $request['employee_id2'],
			'object'  		=> $request['object'],
			'active'        => $request['active'],
        );
        
        $project->updateProject($data);
        
        session()->flash('success',  __('ctrl.data_edit'));
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
        $project = Project::find( $id );
        $project->delete();

        $message = session()->flash('success',  __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }

    public function importProject ()
    {
        try {
            Excel::import(new ProjectsImport, request()->file('file'));
            
            session()->flash('success',  __('ctrl.uploaded'));
            return redirect()->back();

        } catch (Throwable $th) {
            $email = 'jelena.juras@duplico.hr';
            $url = $_SERVER['REQUEST_URI'];
            Mail::to($email)->send(new ErrorMail( $th->getFile() . ' => ' . $th->getMessage(), $url)); 

            session()->flash('error',  __('ctrl.file_error'));
            return redirect()->back();
        }  
    }
}
