<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EvaluationRatingRequest;
use App\Http\Controllers\Controller;
use App\Models\EvaluationRating;
use Sentinel;

class EvaluationRatingController extends Controller
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
		$evaluationRatings = EvaluationRating::get();
		$empl = Sentinel::getUser()->employee;
		$permission_dep = array();
		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
		} 
		
		return view('Centaur::evaluation_ratings.index', ['evaluationRatings' => $evaluationRatings,'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Centaur::evaluation_ratings.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EvaluationRatingRequest $request)
    {
        $data = array(
			'name'		=> $request['name'],
			'rating'	=> $request['rating']
		);
			
		$evaluationRating = new EvaluationRating();
		$evaluationRating->saveEvaluationRating($data);
		
		session()->flash('success', "Podaci su spremljeni");
		
        return redirect()->route('evaluation_ratings.index');
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
        $evaluationRating = EvaluationRating::find($id);

		return view('Centaur::evaluation_ratings.edit', ['evaluationRating' => $evaluationRating]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EvaluationRatingRequest $request, $id)
    {
        $evaluationRating = EvaluationRating::find($id);
		
		$data = array(
			'name'		=> $request['name'],
			'rating'	=> $request['rating']
		);
			
		$evaluationRating->updateEvaluationRating($data);
		
		session()->flash('success', "Podaci su ispravljeni");
		
        return redirect()->route('evaluation_ratings.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $evaluationRating = EvaluationRating::find($id);
		$evaluationRating->delete();
		
		$message = session()->flash('success', 'Ocjena je obrisana.');
		
		return redirect()->back()->withFlashMessage($message);
    }
}
