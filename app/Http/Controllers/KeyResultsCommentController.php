<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\KeyResult;
use App\Models\KeyResultsComment;
use Sentinel;

class KeyResultsCommentController extends Controller
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
    public function create( Request $request )
    {
        if( isset($request['keyResults_id']) ) {
            $keyResults_id = $request['keyResults_id'];
        } else {
            $keyResults_id = null;
        }

        return view('Centaur::key_results_comments.create', ['keyResults_id' => $keyResults_id ]);
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
			'key_results_id'=> $request['key_results_id'],
			'employee_id'  	=> Sentinel::getUser()->employee->id,
			'comment'  		=> $request['comment'],
		);
			
		$keyResultsComment = new KeyResultsComment();
		$keyResultsComment->saveKeyResultsComment($data);

        return 'key_' . $request['key_results_id'];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $keyResult = KeyResult::find($id);
        $comments = $keyResult->hasComments->sortByDesc('created_at');

        return view('Centaur::key_results_comments.show', ['comments' => $comments, 'keyResult_id' => $keyResult->id ]);
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
