<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WorkTask;

class WorkTaskController extends Controller
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
        $workTasks = WorkTask::get();

        return view('Centaur::work_tasks.index', ['workTasks' => $workTasks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Centaur::work_tasks.create');
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
			'name'  	    => $request['name'],
			'description'  	=> $request['description'],
		);
			
		$workTask = new WorkTask();
		$workTask->saveWorkTask($data);
		
		session()->flash('success', __('ctrl.data_save'));
        
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
        $workTask = WorkTask::find($id);

        return view('Centaur::work_tasks.edit', ['workTask' => $workTask]);
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
        $workTask = WorkTask::find($id);

        $data = array(
			'name'  	    => $request['name'],
			'description'  	=> $request['description'],
		);
			
		$workTask->updateWorkTask($data);
		
		session()->flash('success', __('ctrl.data_edit'));
        
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
        $workTask = WorkTask::find($id);
        $workTask->delete();

        $message = session()->flash('success', __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }
}
