<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Models\Training;

class TrainingController extends Controller
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
        $permission_dep = DashboardController::getDepartmentPermission();

        $trainings = Training::get();

        return view('Centaur::trainings.index', ['trainings' => $trainings, 'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Centaur::trainings.create');
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
			'institution'  	=> $request['institution']
		);
			
		$training = new Training();
		$training->saveTraining($data);
		
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
        $training = Training::find($id);

        return view('Centaur::trainings.edit',['training' => $training]);
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
        $training = Training::find($id);
       
        $data = array(
			'name'  	    => $request['name'],
			'description'  	=> $request['description'],
			'institution'  	=> $request['institution']
		);
			
		$training->updateTraining($data);
		
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
        $training = Training::find($id);
        $training->delete();

        $message = session()->flash('success', __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }
}
