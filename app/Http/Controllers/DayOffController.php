<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Models\DayOff;
use App\Models\Employee;
use Sentinel;

class DayOffController extends Controller
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
        $days_off = DayOff::get();

        $permission_dep = DashboardController::getDepartmentPermission();

		return view('Centaur::day_offs.index', ['days_off' => $days_off, 'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::employees_lastNameASC();

        return view('Centaur::day_offs.create', ['employees' => $employees ]);
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
			'employee_id'  	=> $request['employee_id'],
			'comment'  	    => $request['comment'],
			'days_no'  		=> $request['days_no'],
			'user_id'  		=> Sentinel::getUser()->employee->id,
		);
			
		$day_off = new DayOff();
        $day_off->saveDayOff($data);
        
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
        $day_off = DayOff::find($id);
        $employees = Employee::employees_lastNameASC();

        return view('Centaur::day_offs.edit', ['day_off' => $day_off,'employees' => $employees ]);
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
        $day_off = DayOff::find($id);

        $data = array(
			'employee_id'  	=> $request['employee_id'],
			'comment'  	    => $request['comment'],
			'days_no'  		=> $request['days_no'],
			'user_id'  		=> Sentinel::getUser()->employee->id,
		);
	
        $day_off->updateDayOff($data);
        
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
        $day_off = DayOff::find($id);
        $day_off->delete();

        $message = session()->flash('success',  __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }
}
