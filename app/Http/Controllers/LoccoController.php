<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Employee;
use App\Models\Locco;
use App\Models\Emailing;
use App\Models\Department;
use App\Mail\CarServiceMail;
use Illuminate\Support\Facades\Mail;
use Sentinel;

class LoccoController extends Controller
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
        $cars = Car::orderBy('registration','ASC')->get();

        $empl = Sentinel::getUser()->employee;
		$permission_dep = array();
        
		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
        } 

        return view('Centaur::loccos.index', ['cars' => $cars, 'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $cars = Car::orderBy('registration','ASC')->get();
        $employees = Employee::get();

        $reg = null;
        if( $request->get('reg')) {
            $reg = $request->get('reg');
        }      

        return view('Centaur::loccos.create', ['cars' => $cars, 'employees' => $employees, 'registracija' => $reg]);
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
			'car_id'        => $request['car_id'],
			'employee_id'   => $request['employee_id'],
			'date'  	    => $request['date'],
			'destination'   => $request['destination'],
			'start_km'  	=> $request['start_km'],
			'end_km'        => $request['end_km'],
			'distance'      => $request['distance'],
			'comment'       => $request['comment']
            
		);
     
		$locco = new Locco();
        $locco->saveLocco($data);
       
        $car = Car::find($request['car_id']);

        $data_car = array(
            'current_km'  => $request['end_km']
        );

        $car->updateCar($data_car);


        if($request['servis']){
			if(! $request['comment'] ){
				$message = session()->flash('error', __('ctrl.malfunction'));
				return redirect()->back()->withFlashMessage($message);
			} else {
				/* mail obavijest o novoj poruci */
                $emailings = Emailing::get();
                $send_to = array();
                $departments = Department::get();
                $employees = Employee::get();

                if(isset($emailings)) {
                    foreach($emailings as $emailing) {
                        if($emailing->table['name'] == 'loccos' && $emailing->method == 'create') {
                            
                            if($emailing->sent_to_dep) {
                                foreach(explode(",", $emailing->sent_to_dep) as $prima_dep) {
                                    array_push($send_to, $departments->where('id', $prima_dep)->first()->email );
                                }
                            }
                            if($emailing->sent_to_empl) {
                                foreach(explode(",", $emailing->sent_to_empl) as $prima_empl) {
                                    array_push($send_to, $employees->where('id', $prima_empl)->first()->email );
                                }
                            }
                        }
                    }
                }

                foreach(array_unique($send_to) as $send_to_mail) {
                    if( $send_to_mail != null & $send_to_mail != '' ) {
                        Mail::to($send_to_mail)->send(new CarServiceMail($locco)); // mailovi upisani u mailing 
                    }
                }
            }
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
        $loccos = Locco::where('car_id', $id)->orderBy('date','DESC')->get();

        $empl = Sentinel::getUser()->employee;
		$permission_dep = array();
        
		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
        } 

        return view('Centaur::loccos.show', ['loccos' => $loccos, 'car_id' => $id, 'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $locco = Locco::find($id);

        $cars = Car::orderBy('registration','ASC')->get();
        $employees = Employee::get();

        return view('Centaur::loccos.edit', ['locco' => $locco, 'cars' => $cars, 'employees' => $employees]);
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
        $locco = Locco::find($id);

        $data = array(
			'car_id'        => $request['car_id'],
			'employee_id'   => $request['employee_id'],
			'date'  	    => $request['date'],
			'destination'   => $request['destination'],
			'start_km'  	=> $request['start_km'],
			'end_km'        => $request['end_km'],
			'distance'      => $request['distance'],
			'comment'       => $request['comment']
            
		);
     
        $locco->updateLocco($data);
       
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
        $locco = Locco::find($id);

    }
}
