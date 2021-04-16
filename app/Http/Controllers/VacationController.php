<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Vacation;
use App\Models\Department;
use App\Models\Employee;
use Sentinel;

class VacationController extends Controller
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
        $vacations = Vacation::get();
        
        return view('Centaur::vacations.index', ['vacations' => $vacations]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
        $departments = Department::get();
        $employees = Employee::employees_lastNameASC();

        return view('Centaur::vacations.create', ['departments' => $departments,'employees' => $employees]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $plan = array();

        foreach ($request['no_people'] as $key => $id_empl) {
            if( $id_empl != null ) {
                $department_id = $key;
                $plan[$department_id]['no_people'] = $id_empl;
                if (isset($request['employee_id'][ $department_id ])) {
                    $plan[$department_id]['employees'] = implode(',',$request['employee_id'][ $department_id ]);
                }
            }
        }
  
        $plan_text = json_encode($plan);

        $data = array(
			'title'         => $request['title'],
            'description'   => $request['description'],
			'start_period'  => $request['start_period'],
			'end_period'  	=> $request['end_period'],
			'end_date'  	=> $request['end_date'],
			'interval'  	=> $request['interval'],
			'no_week'  	    => $request['no_week'],
            'plan'          => $plan_text,
            'active'  	    => $request['active'],
		);
			
		$vacation = new Vacation();
		$vacation->saveVacation($data);
		
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
        /* $vacation = Vacation::find($id); */
        $vacations = Vacation::whereDate('end_date', '>=', date('Y-m-d'))->with('hasPlans')->get();
        
        $checked_user = Sentinel::getUser();
        $checked_employee =  $checked_user->employee;

        return view('Centaur::vacations.show', [ 'vacations' => $vacations, 'checked_user' => $checked_user, 'checked_employee' => $checked_employee ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vacation = Vacation::find($id);
        $departments = Department::get();
        $employees = Employee::employees_lastNameASC();

        return view('Centaur::vacations.edit', ['vacation' => $vacation,'departments' => $departments,'employees' => $employees]);
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
        $vacation = Vacation::find($id);

        $plan = array();

        foreach ($request['no_people'] as $key => $id_empl) {
            if( $id_empl != null ) {
                $department_id = $key;
                $plan[$department_id]['no_people'] = $id_empl;
                if (isset($request['employee_id'][ $department_id ])) {
                    $plan[$department_id]['employees'] = implode(',',$request['employee_id'][ $department_id ]);
                }
            }
        }
  
        $plan_text = json_encode($plan);

        $data = array(
            'title'         => $request['title'],
            'description'   => $request['description'],
			'start_period'  => $request['start_period'],
			'end_period'  	=> $request['end_period'],
			'end_date'  	=> $request['end_date'],
			'interval'  	=> $request['interval'],
            'no_week'  	    => $request['no_week'],
            'plan'          => $plan_text,
            'active'  	    => $request['active'],
		);
        $vacation->updateVacation($data);

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
        $vacation = Vacation::find($id);
        $vacation->delete();

        $message = session()->flash('success', __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }

}
