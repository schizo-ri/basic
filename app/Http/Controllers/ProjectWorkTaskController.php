<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProjectWorkTask;
use App\Models\WorkTask;
use App\Models\WorkDiary;
use App\Models\Project;

class ProjectWorkTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $projects = Project::get();
        $project = null;

        if(isset($request['project_id']) ) {
            $project = $projects->where('id',$request['project_id'])->first();
        } 
       
        $workTasks = WorkTask::get();

        return view('Centaur::project_work_tasks.create',['workTasks' =>  $workTasks,'project' =>  $project,'projects' =>  $projects]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $project_id = $request['project_id'];

        foreach ($request['task_id'] as $id_task => $hours) {
            $data = array(
                'project_id'  	=>  $project_id,
                'task_id'  	    => $id_task,
                'hours'  		=> $hours ? $hours : 0,
            );
            
            $projectWorkTask = new ProjectWorkTask();
            $projectWorkTask->saveProjectWorkTask($data);
        }
        
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
        $project = Project::where('id', $id)->with('hasProjectWorkTask')->first();
        $projectWorkTasks = $project->hasProjectWorkTask;
        $workTasks = WorkTask::get();

        return view('Centaur::project_work_tasks.edit',['workTasks' =>  $workTasks,'project' =>  $project,'projectWorkTasks' =>  $projectWorkTasks]);
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
       
        $project = Project::where('id', $id)->with('hasProjectWorkTask')->first();
        $projectWorkTasks = $project->hasProjectWorkTask;
       
        foreach ($request['task_id'] as $id_task => $hours) {
            $projectWorkTask =  $projectWorkTasks->where('task_id', $id_task)->first();

            $data = array(
                'project_id'  	=> $project->id,
                'task_id'  	    => $id_task,
                'hours'  		=> $hours ? $hours : 0,
            );
          
            $projectWorkTask->updateProjectWorkTask($data);
        }

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
        //
    }

    public function getProjectTasks (Request $request) 
    {
        $data = array();
        $projectWorkTasks = null;
        $project = Project::where('erp_id', $request['project'])->with('hasProjectWorkTask')->with('hasDiary')->first();
        $workDiaryItem = null;
        if( $project ) {
            $projectWorkTasks = $project->hasProjectWorkTask;
            if( count( $projectWorkTasks) > 0) {
                $worDiaries =  $project->hasDiary;
                $workDiaryItem = collect();
                foreach ($worDiaries as $worDiary) {
                    $workDiaryItem = $workDiaryItem->merge($worDiary->hasWorkDiaryItem);
                }
                $workDiaryItem =  $workDiaryItem->groupBy('task_id');
            }
        }

        $data['projectWorkTasks'] = $projectWorkTasks;
        $data['workDiaryItem'] = $workDiaryItem;
        $data['project_id'] = $project ? $project->id : null;
        
        return $data;
    }
}
