<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Models\TemporaryEmployee;
use App\User;
use App\Models\Work;
use App\Models\Employee;
use App\Mail\TemporaryEmployeeMail;
use App\Mail\ErrorMail;
use Illuminate\Support\Facades\Mail;

class TemporaryEmployeeController extends Controller
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

        $temporary_employees = TemporaryEmployee::get();

        return view('Centaur::temporary_employees.index', ['temporary_employees' => $temporary_employees, 'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::get();
		$works = Work::orderBy('name','ASC')->get();
        $employees = Employee::employees_firstNameASC();
        $temporary_employees = TemporaryEmployee::get();
        return view('Centaur::temporary_employees.create',['users' => $users, 'works' => $works, 'employees' => $employees, 'temporary_employees' => $temporary_employees]);
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
			'user_id'  				=> $request['user_id'],
			'father_name'     		=> $request['father_name'],
			'mather_name'     		=> $request['mather_name'],
			'oib'           		=> $request['oib'],
			'oi'           			=> $request['oi'],
			'oi_expiry'           	=> $request['oi_expiry'],
			'b_day'					=> $request['b_day'],
			'b_place'       		=> $request['b_place'],
			'mobile'  				=> $request['mobile'],
			'priv_mobile'  			=> $request['priv_mobile'],
			'email'  				=> $request['email'],
			'priv_email'  			=> $request['priv_email'],
			'prebiv_adresa'   		=> $request['prebiv_adresa'],
			'prebiv_grad'     		=> $request['prebiv_grad'],
			'title'  			    => $request['title'],
			'qualifications'  		=> $request['qualifications'],
			'marital'  	    		=> $request['marital'],
			'work_id'  	    		=> $request['work_id'],
			'superior_id'  	    	=> $request['superior_id'],
			'reg_date' 	    		=> $request['reg_date'],
			'comment' 	   		    => $request['comment'],
			'checkout' 	   		    => $request['checkout'],
			'size' 	    			=> $request['size'],
			'shoe_size' 	    	=> $request['shoe_size'],
        );
        
        $temporaryEmployee = new TemporaryEmployee();
        $temporaryEmployee->saveTemporaryEmployee($data);
        
        $send_to = EmailingController::sendTo('temporary_employees', 'create');
        try {
            foreach(array_unique($send_to) as $send_to_mail) {
                if( $send_to_mail != null & $send_to_mail != '' )
                Mail::to($send_to_mail)->send(new TemporaryEmployeeMail($temporaryEmployee)); 
            }
        } catch (\Throwable $th) {
            $email = 'jelena.juras@duplico.hr';
            $url = $_SERVER['REQUEST_URI'];
            Mail::to($email)->send(new ErrorMail( $th->getFile() . ' => ' . $th->getMessage(), $url)); 

            session()->flash('error', __('ctrl.data_save') . ', '. __('ctrl.email_error'));
			return redirect()->back();
        }
      
            
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
        $temporaryEmployee = TemporaryEmployee::find($id);
        
        $users = User::get();
		$works = Work::orderBy('name','ASC')->get();
        $employees = Employee::employees_firstNameASC();
        $temporary_employees = TemporaryEmployee::get();
        return view('Centaur::temporary_employees.edit',['temporaryEmployee' => $temporaryEmployee,'users' => $users, 'works' => $works, 'employees' => $employees, 'temporary_employees' => $temporary_employees]);
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
        $temporaryEmployee = TemporaryEmployee::find($id);

        $data = array(
			'user_id'  				=> $request['user_id'],
			'father_name'     		=> $request['father_name'],
			'mather_name'     		=> $request['mather_name'],
			'oib'           		=> $request['oib'],
			'oi'           			=> $request['oi'],
			'oi_expiry'           	=> $request['oi_expiry'],
			'b_day'					=> $request['b_day'],
			'b_place'       		=> $request['b_place'],
			'mobile'  				=> $request['mobile'],
			'priv_mobile'  			=> $request['priv_mobile'],
			'email'  				=> $request['email'],
			'priv_email'  			=> $request['priv_email'],
			'prebiv_adresa'   		=> $request['prebiv_adresa'],
			'prebiv_grad'     		=> $request['prebiv_grad'],
			'title'  			    => $request['title'],
			'qualifications'  		=> $request['qualifications'],
			'marital'  	    		=> $request['marital'],
			'work_id'  	    		=> $request['work_id'],
			'superior_id'  	    	=> $request['superior_id'],
			'reg_date' 	    		=> $request['reg_date'],
			'comment' 	   		    => $request['comment'],
			'checkout' 	   		    => $request['checkout'],
			'size' 	    			=> $request['size'],
			'shoe_size' 	    	=> $request['shoe_size'],
        );
        
        $temporaryEmployee->updateTemporaryEmployee($data);
        
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
        $temporaryEmployee = TemporaryEmployee::find($id);
        $temporaryEmployee->delete();

        $message = session()->flash('success',  __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }
}
