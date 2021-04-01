<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AnnualGoal;

class AnnualGoalController extends Controller
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
        $annual_goals = AnnualGoal::get();

        return view('Centaur::annual_goals.index', ['annual_goals' => $annual_goals]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Centaur::annual_goals.create');
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
			'year'  	    => $request['year'],
			'comment'  		=> $request['comment'],
			'end_date'  	=> $request['end_date'],
		);
			
		$annualGoal = new AnnualGoal();
		$annualGoal->saveAnnualGoal($data);

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
        $annual_goal = AnnualGoal::find($id);

        return view('Centaur::annual_goals.edit', ['annual_goal' => $annual_goal]);
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
        $annual_goal = AnnualGoal::find($id);

        $data = array(
			'name'  	    => $request['name'],
			'year'  	    => $request['year'],
			'comment'  		=> $request['comment'],
			'end_date'  	=> $request['end_date'],
		);

        $annual_goal->updateAnnualGoal($data);

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
        $annual_goal = AnnualGoal::find($id);
        $annual_goal->delete();

        session()->flash('success',  __('ctrl.data_delete'));
		
        return redirect()->back();
    }
}
