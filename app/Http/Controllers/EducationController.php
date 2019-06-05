<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EducationRequest;
use App\Http\Controllers\Controller;
use App\Models\Education;
use App\Models\Department;

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
        $educations = Education::get();
		$departments = Department::get();
		
		return view('Centaur::education.index', ['educations' => $educations, 'departments' => $departments]);
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
		
		return view('Centaur::education.create',['departments0'=>$departments0, 'departments1'=>$departments1, 'departments2'=>$departments2]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EducationRequest $request)
    {
		$to_department_id = implode(",", $request['to_department_id'] );
		
		$data = array(
			'name'  			=> $request['name'],
			'to_department_id'  => $to_department_id,
			'status'  	 		=> $request['status']
		);
			
		$education = new Education();
		$education->saveEducation($data);
		
		session()->flash('success', "Podaci su spremljeni");
		
        return redirect()->route('education.index');
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
        $education = Education::find($id);
		$departments0 = Department::where('level1',0)->orderBy('name','ASC')->get();
		$departments1 = Department::where('level1',1)->orderBy('name','ASC')->get();
		$departments2 = Department::where('level1',2)->orderBy('name','ASC')->get();
		
		return view('Centaur::education.edit',['education'=>$education,'departments0'=>$departments0, 'departments1'=>$departments1, 'departments2'=>$departments2]);
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
	   
		$to_department_id = implode(",", $request['to_department_id'] );
		
		$data = array(
			'name'  	 => $request['name'],
			'to_department_id'  => $to_department_id,
			'status'  	 => $request['status']
		);
		
		$education->updateEducation($data);
		
		session()->flash('success', "Podaci su ispravljeni");
		
        return redirect()->route('education.index');
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
		$education->delete();
		
		$message = session()->flash('success', 'Edukacija je obrisana.');
		
		return redirect()->back()->withFlashMessage($message);
    }
}
