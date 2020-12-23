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
use App\User;
use Sentinel;
use App\Mail\EmployeeCreate;
use Illuminate\Support\Facades\Mail;
use App\Models\Emailing;
use App\Models\Department;

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
		$employees = Employee::where('id','<>',1)->where('checkout',null)->get();
		$empl = Sentinel::getUser()->employee;
		$permission_dep = array();
        
		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
        } 
		
		return view('Centaur::employees.index', ['employees' => $employees, 'permission_dep' => $permission_dep]);
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
		$employees = Employee::where('id','<>',1)->where('checkout',null)->get();
	
		$campaigns = Campaign::where('type','evergreen')->get();
		$moduli = CompanyController::getModules(); // provjera da li se koriste moduli kampanja

		if(isset($request['user_id'])) {
			$user1 = User::find($request->user_id);
			return view('Centaur::employees.create', ['works' => $works,'employees' => $employees, 'campaigns' => $campaigns,'moduli' => $moduli,'user1' => $user1, 'users' => $users]);
		} else {
			return view('Centaur::employees.create', ['works' => $works, 'employees' => $employees,'campaigns' => $campaigns,'moduli' => $moduli,'users' => $users]);
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
		
		if( $input['stazY'] ){
			$stazY = $input['stazY'];
		} else {
			$stazY = 0;
		}
		if( $input['stazM'] ){
			$stazM = $input['stazM'];
		} else {
			$stazM = 0;
		}
		if( $input['stazD'] ){
			$stazD = $input['stazD'];
		} else {
			$stazD = 0;
		}
		$staz =$stazY.'-'.$stazM.'-'.$stazD;
		
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
		
		$abs_days = array();
		if( $request['abs_days'] ) {
			foreach ($request['abs_days'] as $key => $abs_day) {
				if( $abs_day != '' && $request['abs_year'][$key] != '' && $request['abs_year'][$key] ) 
				$abs_days[$request['abs_year'][$key]] = $abs_day;
			}
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
			'comment' 	   		    => $input['comment'],
			'color' 	   		    => $input['color'],
			'abs_days' 	    		=> count($abs_days) > 0 ? serialize($abs_days) : null
		);
		
		if( $input['superior_id'] != 0 ) {
			$data += ['superior_id'  => $input['superior_id']];
		} 
		if( $request ['effective_cost']) {
			$data += ['effective_cost'  => str_replace(',','.', $input['effective_cost'])];
		} 
		if( $request ['brutto']) {
			$data += ['brutto'  => str_replace(',','.', $input['brutto'])];
		} 
		
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
		
		/* mail obavijest  */
		$send_to = EmailingController::sendTo('employees', 'create');
		try {
			foreach(array_unique($send_to) as $send_to_mail) {
				if( $send_to_mail != null & $send_to_mail != '' )
				Mail::to($send_to_mail)->send(new EmployeeCreate($employee)); // mailovi upisani u mailing 
			}
		} catch (\Throwable $th) {
			session()->flash('error', __('ctrl.data_save') . ', '. __('ctrl.email_error'));
			return redirect()->back();
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
		$employees = Employee::where('id','<>',1)->where('checkout',null)->get();
		$campaigns = Campaign::where('type','evergreen')->get();
		$campaignRecipients = CampaignRecipient::where('employee_id',$employee->id )->get();

		$moduli = CompanyController::getModules(); // provjera da li se koriste moduli kampanja

		return view('Centaur::employees.edit', ['works' => $works, 'users' => $users,'moduli' => $moduli, 'employee' => $employee, 'employees' => $employees,'campaigns' => $campaigns,'campaignRecipients' => $campaignRecipients]);
		
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
		
		$staz = $input['stazY'].'-'.$input['stazM'].'-'.$input['stazD'];
		if(!isset($input['termination_service']) || $input['termination_service'] == '') {
			$termination_service = null;
		} else {
			$termination_service = $input['termination_service'];
		}
		if(! isset($input['first_job']) || $input['first_job'] == '' ) {
			$first_job = null;
		} else {
			$first_job = $input['first_job'];
		}
	
		$abs_days = array();
		if( $request['abs_days']) {
			foreach ($request['abs_days'] as $key => $abs_day) {
				if( $abs_day != ''  && $request['abs_year'][$key] != '' && $request['abs_year'][$key] ) 
				$abs_days[$request['abs_year'][$key]] = $abs_day;
			}
		}
	
		$data = array(
			'user_id'  				=> intval($input['user_id']),
			'father_name'     		=> $input['father_name'] == '' ? null : $input['father_name'],
			'mather_name'     		=> $input['mather_name'] == '' ? null : $input['mather_name'],
			'oib'           		=> $input['oib'] == '' ? null : $input['oib'],
			'oi'           			=> $input['oi'] == '' ? null : $input['oi'],
			'oi_expiry'           	=> $input['oi_expiry'] == '' ? null : $input['oi_expiry'],
			'b_day'					=> $input['b_day'] == '' ? null : $input['b_day'],
			'b_place'       		=> $input['b_place'] == '' ? null : $input['b_place'],
			'mobile'  				=> $input['mobile'] == '' ? null : $input['mobile'],
			'priv_mobile'  			=> $input['priv_mobile'] == '' ? null : $input['priv_mobile'],
			'email'  				=> $input['email'] == '' ? null : $input['email'],
			'priv_email'  			=> $input['priv_email'] == '' ? null : $input['priv_email'],
			'prebiv_adresa'   		=> $input['prebiv_adresa'] == '' ? null : $input['prebiv_adresa'],
			'prebiv_grad'     		=> $input['prebiv_grad'] == '' ? null : $input['prebiv_grad'],
			'borav_adresa'      	=> $input['borav_adresa'] == '' ? null : $input['borav_adresa'],
			'borav_grad'        	=> $input['borav_grad'] == '' ? null : $input['borav_grad'],
			'title'  			    => $input['title'] == '' ? null : $input['title'],
			'qualifications'  		=> $input['qualifications'] == '' ? null : $input['qualifications'],
			'marital'  	    		=> $input['marital'] == '' ? null : $input['marital'],
			'work_id'  	    		=> $input['work_id'] == '' ? null : intval($input['work_id']),
			'reg_date' 	    		=> $input['reg_date'] == '' ? null : $input['reg_date'],
			'checkout' 	    		=> $input['checkout'] == '' ? null : $input['checkout'],
			'probation' 	   		=> $input['probation'] == '' ? null : intval($input['probation']),
			'years_service' 	   	=> $staz,
			'termination_service' 	=> $termination_service,
			'first_job' 			=> $first_job,
			'comment' 	   		    => $input['comment'] == '' ? null : $input['comment'],
			'color' 	   		    => $input['color'],
			'abs_days' 	    		=> count($abs_days) > 0 ? serialize($abs_days) : null
		);
		
		if( $input['superior_id'] != 0 ) {
			$data += ['superior_id'  => $input['b_day'] == '' ? null : intval($input['superior_id'])];
		} 
		if( $request ['effective_cost']) {
			$data += ['effective_cost'  => str_replace(',','.', $input['effective_cost'])];
		} 
		if( $request ['brutto']) {
			$data += ['brutto'  => str_replace(',','.', $input['brutto'])];
		} 
		
		$employee->updateEmployee($data);
	
		// za odjavljenog djelatnika - korisnički kodaci deaktivirani
		if($input['checkout'] != '') {
			$user = Sentinel::findById($employee->user_id );

			$credentials = [
				'active' => 0,
				'password' => 'otkaz123',
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
		
		/* mail obavijest  */
		$send_to = EmailingController::sendTo('employees', 'update');
		try {
			foreach($send_to as $send_to_mail) {
				if( $send_to_mail != null & $send_to_mail != '' )
				Mail::to($send_to_mail)->send(new EmployeeCreate($employee)); // mailovi upisani u mailing 
			}
		} catch (\Throwable $th) {
			session()->flash('error', __('ctrl.data_save') . ', '. __('ctrl.email_error'));
			return redirect()->back();
		}
		

		session()->flash('success', __('ctrl.data_edit'));
		return redirect()->back();
       // return redirect()->route('employees.index');
		
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
