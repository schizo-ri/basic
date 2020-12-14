<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EmployeeRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailingController;
use App\Http\Controllers\CompanyController;
use App\Models\Work;
use App\Models\Employee;
use App\Models\Campaign;
use App\Models\CampaignRecipient;
use App\Models\EmployeeDepartment;
use App\Models\EmployeeTermination;
use App\User;
use Sentinel;
use App\Mail\EmployeeCreate;
use Illuminate\Support\Facades\Mail;
use App\Models\Emailing;
use App\Models\Department;
use Log;
use Illuminate\Support\Facades\Hash;

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
    public function index(Request $request)
    {
		if( isset($request['status'])) {
			if( $request['status'] == 'checkout' ) {
				$status = 0;
			} else {
				$status = 1;
			}
		} else {
			$status = 1;
		}

		$employees = Employee::employees_lastNameASCStatus($status); 
		$empl = Sentinel::getUser()->employee;
		$permission_dep = array();
        
		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
        } 
		
		return view('Centaur::employees.index', ['employees' => $employees, 'permission_dep' => $permission_dep]);
	}
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function contacts()
    {
		$employees = Employee::employees_lastNameASC();
		
		return view('Centaur::contacts', ['employees' => $employees]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$users = User::where('active', 1)->orderBy('last_name','ASC')->get();
		$departments = Department::orderBy('name','ASC')->get();
	 	$works = Work::orderBy('name','ASC')->get();
		$employees = Employee::employees_lastNameASC();
		
		$campaigns = Campaign::where('type','evergreen')->get();
		$moduli = CompanyController::getModules(); // provjera da li se koriste moduli kampanja

		if(isset($request['user_id'])) {
			$user1 = User::find($request->user_id);
			return view('Centaur::employees.create', ['works' => $works,'departments' => $departments,'employees' => $employees, 'campaigns' => $campaigns,'moduli' => $moduli,'user1' => $user1, 'users' => $users]);
		} else {
			return view('Centaur::employees.create', ['works' => $works,'departments' => $departments, 'employees' => $employees,'campaigns' => $campaigns,'moduli' => $moduli,'users' => $users]);
		}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->except(['_token']);
		
		$stazY = 0;
		$stazM = 0;
		$stazD = 0;

		if( $input['stazY'] ){
			$stazY = $input['stazY'];
		} 
		if( $input['stazM'] ){
			$stazM = $input['stazM'];
		}
		if( $input['stazD'] ){
			$stazD = $input['stazD'];
		}
		$staz =$stazY.'-'.$stazM.'-'.$stazD;
		
		$abs_days = array();
		if( $request['abs_days'] ) {
			foreach ($request['abs_days'] as $key => $abs_day) {
				if( $abs_day != '' && $abs_day != 0 && $request['abs_year'][$key] != '' && $request['abs_year'][$key] ) 
				$abs_days[$request['abs_year'][$key]] = $abs_day;
			}
		}

		$data = array(
			'user_id'  				=> $input['user_id'],
			'erp_id'  				=> $input['erp_id'],
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
			'years_service' 	   	=> $input['stazY'].'-'.$input['stazM'].'-'.$input['stazD'],
			'termination_service' 	=> isset($input['termination_service']) && $input['termination_service'] ? $input['termination_service'] : null,
			'first_job' 			=> isset($input['first_job']) && $input['first_job'] ? $input['first_job'] : null,
			'comment' 	   		    => $input['comment'],
			'color' 	   		    => $input['color'],
			'abs_days' 	    		=> count($abs_days) > 0 ? serialize($abs_days) : null,
			'maiden_name' 	    	=> $input['maiden_name'],
			'lijecn_pregled' 	    => $input['lijecn_pregled'],
			'znr' 	    			=> $input['znr'],
			'size' 	    			=> $input['size'],
			'shoe_size' 	    	=> $input['shoe_size'],
			'days_off' 	    		=> $input['days_off'] ? $input['days_off'] : 0,
			'stranger' 	    		=> isset( $input['stranger']) ? $input['stranger'] : 0,
			'permission_date' 	    => $input['permission_date'],
			'superior_id' 	    	=> $input['superior_id'] != 0 ?  $input['superior_id'] : null,
			'effective_cost' 	    => $input['effective_cost'] ? str_replace(',','.', $input['effective_cost']) : null,
			'brutto' 	    		=> $input['brutto'] ? str_replace(',','.', $input['brutto']) : null,
		);
	
		$employee = new Employee();
		$employee->saveEmployee($data);
		
		if(isset( $input['campaign_id']) && count($input['campaign_id']) > 0) {
			foreach ($input['campaign_id'] as $campaign_id) {
				$data_campaign = array(
					'campaign_id' => $campaign_id,
					'employee_id' => $employee->id,
				);
				$campaignRecipient = new CampaignRecipient();
				$campaignRecipient->saveCampaignRecipient($data_campaign);
			}
		}
		if(isset( $input['department_id']) && count($input['department_id']) > 0) {
			foreach ($input['department_id'] as $department_id) {
				$data_department = array(
					'department_id' => $department_id,
					'employee_id' => $employee->id,
				);
				$employeeDepartment = new EmployeeDepartment();
				$employeeDepartment->saveEmployeeDepartment($data_department);
			}
		}
		
		/* mail obavijest o novoj poruci */
		$send_to = EmailingController::sendTo('employees', 'create');
		Log::info( 'create employee' );
		Log::info( $send_to );
		if($request['send_email'] == 'DA') {
			try {
				foreach(array_unique($send_to) as $send_to_mail) {
					if( $send_to_mail != null & $send_to_mail != '' )
					Mail::to($send_to_mail)->send(new EmployeeCreate($employee)); 
				}
			} catch (\Throwable $th) {
				session()->flash('error', __('ctrl.data_save') . ', '. __('ctrl.email_error'));
				return redirect()->back();
			}
		}
		
		session()->flash('success',  __('ctrl.data_save'));
		return redirect()->back();
    //    return redirect()->route('employees.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = Employee::find($id);		
		
		$user_name = explode('.',strstr($employee->email,'@',true));
		if(count($user_name) == 2) {
			$user_name = $user_name[1] . '_' . $user_name[0];
		} else {
			$user_name = $user_name[0];
		}

		$path = 'storage/' . $user_name . "/profile_img/";
		if(file_exists($path)){
			$docs = array_diff(scandir($path), array('..', '.', '.gitignore'));
		}else {
			$docs = '';
		}

		return view('Centaur::employees.show', ['employee' => $employee,'docs' => $docs,'user_name' => $user_name]);
	}
	
	public function showPrint($id)
    {
        $employee = Employee::find($id);		
		
		$user_name = explode('.',strstr($employee->email,'@',true));
		if(count($user_name) == 2) {
			$user_name = $user_name[1] . '_' . $user_name[0];
		} else {
			$user_name = $user_name[0];
		}

		$path = 'storage/' . $user_name . "/profile_img/";
		if(file_exists($path)){
			$docs = array_diff(scandir($path), array('..', '.', '.gitignore'));
		}else {
			$docs = '';
		}

		return view('Centaur::employees.show_print', ['employee' => $employee,'docs' => $docs,'user_name' => $user_name]);
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
		$users = User::where('active',1)->orderBy('last_name','ASC')->get();
		$works = Work::orderBy('name','ASC')->get();
		$departments = Department::orderBy('name','ASC')->get();
		$employees = Employee::employees_lastNameASC();
		$campaigns = Campaign::where('type','evergreen')->get();
		$campaignRecipients = CampaignRecipient::where('employee_id',$employee->id )->get();

		$moduli = CompanyController::getModules(); // provjera da li se koriste moduli kampanja

		return view('Centaur::employees.edit', ['works' => $works, 'departments' => $departments,'users' => $users,'moduli' => $moduli, 'employee' => $employee, 'employees' => $employees,'campaigns' => $campaigns,'campaignRecipients' => $campaignRecipients]);
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
		$employee = Employee::find($id);
		$input = $request->except(['_token']);
	
		$abs_days = array();
		if( $request['abs_days']) {
			foreach ($request['abs_days'] as $key => $abs_day) {
				if( $abs_day != '' && $abs_day != 0 && $request['abs_year'][$key] != '' && $request['abs_year'][$key] ) 
				$abs_days[$request['abs_year'][$key]] = $abs_day;
			}
		}
	
		$data = array(
			'user_id'  				=> $input['user_id'],
			'erp_id'  				=> $input['erp_id'],
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
			'years_service' 	   	=> $input['stazY'].'-'.$input['stazM'].'-'.$input['stazD'],
			'termination_service' 	=> isset($input['termination_service']) && $input['termination_service'] ? $input['termination_service'] : null,
			'first_job' 			=> isset($input['first_job']) && $input['first_job'] ? $input['first_job'] : null,
			'comment' 	   		    => $input['comment'],
			'color' 	   		    => $input['color'],
			'abs_days' 	    		=> count($abs_days) > 0 ? serialize($abs_days) : null,
			'maiden_name' 	    	=> $input['maiden_name'],
			'lijecn_pregled' 	    => $input['lijecn_pregled'],
			'znr' 	    			=> $input['znr'],
			'size' 	    			=> $input['size'],
			'shoe_size' 	    	=> $input['shoe_size'],
			'days_off' 	    		=> $input['days_off'] ? $input['days_off'] : 0,
			'stranger' 	    		=> isset( $input['stranger']) && $input['stranger'] ? $input['stranger'] : 0,
			'permission_date' 	    => isset($input['permission_date']) && $input['permission_date'] ? $input['permission_date'] : null,
			'superior_id' 	    	=> isset($input['superior_id']) && $input['superior_id'] != 0 ?  $input['superior_id'] : null,
			'effective_cost' 	    => isset($input['effective_cost']) && $input['effective_cost'] ? str_replace(',','.', $input['effective_cost']) : null,
			'brutto' 	    		=> isset($input['brutto']) && $input['brutto'] ? str_replace(',','.', $input['brutto']) : null,
		);
		
		$employee->updateEmployee($data);
	
		// za odjavljenog djelatnika - korisnički kodaci deaktivirani
		if( $input['checkout'] ) {
			$user = Sentinel::findById($employee->user_id );

			$credentials = [
				'active' => 0,
				'password' => Hash::make('otkaz123'),
			];

			$user = Sentinel::update($user, $credentials);
		}

		if(isset($input['campaign_id']) && $input['campaign_id']) {
			$campaignRecipients = CampaignRecipient::where('employee_id', $employee->id)->get();
			foreach ($input['campaign_id'] as $campaign_id) {
				if( ! $campaignRecipients->where('campaign_id', $campaign_id )->first()) {
					$data_campaign = array(
						'campaign_id' => $campaign_id,
						'employee_id' => $employee->id,
					);
					$campaignRecipient = new CampaignRecipient();
					$campaignRecipient->saveCampaignRecipient($data_campaign);
				}
			}
		 	foreach ($campaignRecipients as $recipient) {
				if(! in_array( $recipient->campaign_id, $input['campaign_id'])) {
					$recipient->delete();
				}
			} 
		}
		
		if(isset( $input['department_id']) && count($input['department_id']) > 0) {
			$employeeDepartments = EmployeeDepartment::where('employee_id', $employee->id)->get();
			foreach ($input['department_id'] as $department_id) {
				if( ! $employeeDepartments->where('department_id', $department_id)->first() ) {
					$data_department = array(
						'department_id' => $department_id,
						'employee_id' => $employee->id,
					);
					$employeeDepartment = new EmployeeDepartment();
					$employeeDepartment->saveEmployeeDepartment($data_department);
				}
			}
			foreach ($employeeDepartments as $employeeDepartment) {
				if( ! in_array( $employeeDepartment->department_id , $input['department_id'] )) {
					$employeeDepartment->delete();
				}
			}
		}

		/* mail obavijest o novoj poruci */
		$send_to = EmailingController::sendTo('employees', 'update');
		Log::info( 'edit employee' );
		Log::info( $send_to );
		if($request['send_email'] == 'DA') {
			Log::info(" request['send_email'] = DA" );
			$send_to = EmailingController::sendTo('employees', 'update');
			try {
				foreach($send_to as $send_to_mail) {
					if( $send_to_mail != null & $send_to_mail != '' ) {
						Mail::to($send_to_mail)->send(new EmployeeCreate($employee)); // mailovi upisani u mailing 
					}
				}
			} catch (\Throwable $th) {
				session()->flash('error', __('ctrl.data_save') . ', '. __('ctrl.email_error'));
				return redirect()->back();
			}
		}

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
        $employee = Employee::find($id);
		$employee->delete();
		
		$message = session()->flash('success',  __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }
}
