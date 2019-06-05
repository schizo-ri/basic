<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EmployeeRequest;
use App\Http\Controllers\Controller;
use App\Models\Work;
use App\Models\Employee;
use App\User;

class EmployeeController extends Controller
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
        $employees = Employee::get();
		
		return view('Centaur::employees.index', ['employees' => $employees]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$users = User::get();
		$works = Work::get();
		
		if(isset($request->user_id)) {
			$user1 = User::find($request->user_id);
			return view('Centaur::employees.create', ['works' => $works, 'user1' => $user1, 'users' => $users]);
		} else {
			return view('Centaur::employees.create', ['works' => $works, 'users' => $users]);
		}
		
		
		
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeRequest $request)
    {
        $input = $request->except(['_token']);
		
		$staz = $input['stazY'].'-'.$input['stazM'].'-'.$input['stazD'];
		
		if(!isset($input['termination_service'])) {
			$termination_service = null;
		} else {
			$termination_service = $input['termination_service'];
		}
		if(!isset($input['first_job'])) {
			$first_job = null;
		} else {
			$first_job = $input['first_job'];
		}
		
		$data = array(
			'user_id'  				=> $input['user_id'],
			'father_name'     		=> $input['father_name'],
			'mather_name'     		=> $input['mather_name'],
			'oib'           		=> $input['oib'],
			'oi'           			=> $input['oi'],
			'oi_expiry'           	=> $input['oi_expiry'],
			'b_day'					=> $input['b_day'],
			'b_place'       		=> $input['b_place'],
			'mobile'  				=> $input['mobile'],
			'priv_mobile'  			=> $input['priv_mobile'],
			'email'  				=> $input['email'],
			'priv_email'  			=> $input['priv_email'],
			'prebiv_adresa'   		=> $input['prebiv_adresa'],
			'prebiv_grad'     		=> $input['prebiv_grad'],
			'borav_adresa'      	=> $input['borav_adresa'],
			'borav_grad'        	=> $input['borav_grad'],
			'title'  			    => $input['title'],
			'qualifications'  		=> $input['qualifications'],
			'marital'  	    		=> $input['marital'],
			'work_id'  	    		=> $input['work_id'],
			'reg_date' 	    		=> $input['reg_date'],
			'probation' 	   		=> $input['probation'],
			'years_service' 	   	=> $staz,
			'termination_service' 	=> $termination_service,
			'first_job' 			=> $first_job,
			'comment' 	   		    => $input['comment']
		);
		
		$employee = new Employee();
		$employee->saveEmployee($data);
		
		session()->flash('success', "Podaci su spremljeni");
		
        return redirect()->route('employees.index');
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
		$employee = Employee::find($id);
		$users = User::get();
		$works = Work:: get();
		
		return view('Centaur::employees.edit', ['works' => $works, 'users' => $users, 'employee' => $employee]);
		
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeeRequest $request, $id)
    {
		$employee = Employee::find($id);
		
		$input = $request->except(['_token']);
		
		$staz = $input['stazY'].'-'.$input['stazM'].'-'.$input['stazD'];
		if(!isset($input['termination_service'])) {
			$termination_service = null;
		} else {
			$termination_service = $input['termination_service'];
		}
		if(!isset($input['first_job'])) {
			$first_job = null;
		} else {
			$first_job = $input['first_job'];
		}
		$data = array(
			'user_id'  				=> $input['user_id'],
			'father_name'     		=> $input['father_name'],
			'mather_name'     		=> $input['mather_name'],
			'oib'           		=> $input['oib'],
			'oi'           			=> $input['oi'],
			'oi_expiry'           	=> $input['oi_expiry'],
			'b_day'					=> $input['b_day'],
			'b_place'       		=> $input['b_place'],
			'mobile'  				=> $input['mobile'],
			'priv_mobile'  			=> $input['priv_mobile'],
			'email'  				=> $input['email'],
			'priv_email'  			=> $input['priv_email'],
			'prebiv_adresa'   		=> $input['prebiv_adresa'],
			'prebiv_grad'     		=> $input['prebiv_grad'],
			'borav_adresa'      	=> $input['borav_adresa'],
			'borav_grad'        	=> $input['borav_grad'],
			'title'  			    => $input['title'],
			'qualifications'  		=> $input['qualifications'],
			'marital'  	    		=> $input['marital'],
			'work_id'  	    		=> $input['work_id'],
			'reg_date' 	    		=> $input['reg_date'],
			'probation' 	   		=> $input['probation'],
			'years_service' 	   	=> $staz,
			'termination_service' 	=> $termination_service,
			'first_job' 			=> $first_job,
			'comment' 	   		    => $input['comment']
		);

		$employee->updateEmployee($data);
		
		session()->flash('success', "Podaci su ispravljeni");
		
        return redirect()->route('employees.index');
		
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Employee::find($id);
		$employee->delete();
		
		$message = session()->flash('success', 'Zaposlenik je obrisan.');
		
		return redirect()->back()->withFlashMessage($message);
    }
}
