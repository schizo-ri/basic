<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Models\Kid;
use App\Models\Employee;

class KidController extends Controller
{
    /**
     * Set middleware to quard controller.
     *
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
        $kids = Kid::get();
        $permission_dep = DashboardController::getDepartmentPermission();

        return view('Centaur::kids.index', ['kids' => $kids,'permission_dep' => $permission_dep ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Employee::employees_firstNameASC();

        return view('Centaur::kids.create', ['employees' => $employees]);
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
			'first_name'    => $request['first_name'],
			'last_name'     => $request['last_name'],
			'employee_id'   => $request['employee_id'],
			'b_day'          => $request['b_day'],
		);
		
		$kid = new Kid();
        $kid->saveKid($data);
        
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
        $kid = Kid::find($id);
        $employees = Employee::employees_firstNameASC();

        return view('Centaur::kids.edit', ['kid' => $kid, 'employees' => $employees]);
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
        $kid = Kid::find($id);

        $data = array(
			'first_name'    => $request['first_name'],
			'last_name'     => $request['last_name'],
			'employee_id'   => $request['employee_id'],
			'b_day'          => $request['b_day'],
		);
		
        $kid->updateKid($data);
        
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
        $kid = Kid::find($id);
        $kid->delete();

        $message = session()->flash('success',  __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }
}
