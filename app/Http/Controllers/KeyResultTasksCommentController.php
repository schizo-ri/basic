<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\KeyResultTask;
use App\Models\KeyResultTasksComment;
use Sentinel;

class KeyResultTasksCommentController extends Controller
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
			'key_result_tasks_id'=> $request['key_result_tasks_id'],
			'employee_id'  	=> Sentinel::getUser()->employee->id,
			'comment'  		=> $request['comment'],
		);
			
		$keyResultTasksComment = new KeyResultTasksComment();
		$keyResultTasksComment->saveKeyResultTasksComment($data);

        return 'task_' . $request['key_results_id'];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = KeyResultTask::find($id);
        $comments = $task->hasComments->sortByDesc('created_at');

        return view('Centaur::key_result_tasks_comments.show', ['comments' => $comments, 'task_id' => $task->id ]);
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
        //
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
}
