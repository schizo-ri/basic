<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Models\JobInterview;
use App\Models\Work;

class JobInterviewController extends Controller
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
        $job_interviews = JobInterview::get();
        $permission_dep = DashboardController::getDepartmentPermission();

        return view('Centaur::job_interviews.index',['job_interviews'=>$job_interviews, 'permission_dep'=>$permission_dep]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $works = Work::get();

        return view('Centaur::job_interviews.create',['works'=>$works ]);
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
			'first_name'     	=> $request['first_name'],
			'last_name'     	=> $request['last_name'],
			'oib'           	=> $request['oib'],
			'date'				=> $request['date'],
			'phone'  			=> $request['phone'],
			'email'  			=> $request['email'],
			'title'  			=> $request['title'],
			'qualifications'  	=> $request['qualifications'],
			'work_id'  	    	=> $request['work_id'],
			'years_service' 	=> $request['years_service'],
			'language' 	        => $request['language'],
			'salary' 	   	    => $request['salary'],
			'comment' 	   	    => $request['comment'],
        );
        
        $jobInterview = new JobInterview();
        $jobInterview->saveJobInterview($data);
        
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
        $job_interview = JobInterview::find($id);

        return view('Centaur::job_interviews.show',['job_interview'=>$job_interview ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $job_interview = JobInterview::find($id);
        $works = Work::get();

        return view('Centaur::job_interviews.edit',['job_interview'=>$job_interview, 'works'=>$works]);
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
        $job_interview = JobInterview::find($id);
        
        $data = array(
			'first_name'     	=> $request['first_name'],
			'last_name'     	=> $request['last_name'],
			'oib'           	=> $request['oib'],
			'date'				=> $request['date'],
			'phone'  			=> $request['phone'],
			'email'  			=> $request['email'],
			'title'  			=> $request['title'],
			'qualifications'  	=> $request['qualifications'],
			'work_id'  	    	=> $request['work_id'],
			'years_service' 	=> $request['years_service'],
			'language' 	        => $request['language'],
			'salary' 	   	    => $request['salary'],
			'comment' 	   	    => $request['comment'],
        );
        
        $job_interview->updateJobInterview($data);
        
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
        $job_interview = JobInterview::find($id);
        $job_interview->delete();

        $message = session()->flash('success',  __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }
}
