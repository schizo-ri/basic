<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EducationRequest;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Controller;
use App\Models\Education;
use App\Models\EducationTheme;
use App\Models\Department;
use App\Models\Employee;
use Sentinel;

class EducationController extends Controller
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
        $educations = Education::orderBy('name','ASC')->get();
       
        if(! Sentinel::inRole('administrator')) {
            $employee = Sentinel::getUser()->employee;
           
            if( $employee ) {
                $user_departments_id = Employee::employeesDepartment( $employee);
                $education_employee = collect();

                if( count( $user_departments_id ) > 0 ) {
                    foreach ($user_departments_id as $department_id) {
                        $education_employee =  $education_employee->merge($educations->where('to_department_id', $department_id));
                    }
                    $educations = $education_employee;
                } else {
                    $educations = null;
                }
               
            } else {
                $educations = null;
            }
        } 
        $permission_dep = DashboardController::getDepartmentPermission();

		return view('Centaur::educations.index', ['educations' => $educations,'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments0 = Department::where('level1',0)->orderBy('name','ASC')->get();
		$departments1 = Department::where('level1',1)->orderBy('name','ASC')->get();
		$departments2 = Department::where('level1',2)->orderBy('name','ASC')->get();
		
		return view('Centaur::educations.create',['departments0'=>$departments0, 'departments1'=>$departments1, 'departments2'=>$departments2]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	/* 	$to_department_id = implode(",", $request['to_department_id'] ); */
        
        foreach ($request['to_department_id'] as $department_id ) {
            $data = array(
                'name'  			=> $request['name'],
                'to_department_id'  => $department_id,
                'status'  	 		=> $request['status']
            );
                
            $education = new Education();
            $education->saveEducation($data);
        }
	
		session()->flash('success',  __('ctrl.data_save'));
		
        return redirect()->route('educations.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $education = Education::find($id);
        $permission_dep = DashboardController::getDepartmentPermission();

        return view('Centaur::educations.show',[ 'education'=>$education, 'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $education = Education::find($id);
        $departments0 = Department::where('level1',0)->orderBy('name','ASC')->get();
		$departments1 = Department::where('level1',1)->orderBy('name','ASC')->get();
        $departments2 = Department::where('level1',2)->orderBy('name','ASC')->get();
			
		return view('Centaur::educations.edit',['education'=>$education,'departments0'=>$departments0, 'departments1'=>$departments1, 'departments2'=>$departments2]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EducationRequest $request, $id)
    {
        $education = Education::find($id);
        
        $data = array(
            'name'  			=> $request['name'],
            'to_department_id'  =>  $request['to_department_id'],
            'status'  	 		=> $request['status']
        );

        $education->updateEducation($data);
		
		session()->flash('success', __('ctrl.data_edit'));
		
        return redirect()->route('educations.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $education = Education::find($id);

        if(count($education->educationThemes) > 0) {
            foreach($education->educationThemes as $theme) {
                if(count($theme->educationArticles) > 0) {
                    foreach($theme->educationArticles as $article) {
                        $article->delete();
                    }
                }
                $theme->delete();
            }
        }
		$education->delete();
		
		$message = session()->flash('success',  __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }
}
