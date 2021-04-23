<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Okr;
use App\Models\OkrComment;
use Sentinel;

class OkrCommentController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request )
    {
        if( isset($request['okr_id']) ) {
            $okr_id = $request['okr_id'];
        } else {
            $okr_id = null;
        }

        return view('Centaur::okr_comments.create', ['okr_id' => $okr_id ]);
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
			'okr_id'  	=> $request['okr_id'],
			'employee_id'  	=> Sentinel::getUser()->employee->id,
			'comment'  		=> $request['comment'],
		);
			
		$okrComment = new OkrComment();
		$okrComment->saveOkrComment($data);

        return 'okr_' .  $request['okr_id'];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $okr = Okr::find($id);
        $comments = $okr->hasComments->sortByDesc('created_at');

        return view('Centaur::okr_comments.show', ['comments' => $comments, 'okr_id' => $okr->id ]);
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
