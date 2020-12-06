<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailingController;
use App\Models\Car;
use App\Models\Employee;
use App\Models\Locco;
use App\Models\Emailing;
use App\Models\TravelOrder;
use App\Models\TravelLocco;
use App\Models\Department;
use App\Models\WorkRecord;
use App\Mail\CarServiceMail;
use App\Mail\TravelCreate;
use Illuminate\Support\Facades\Mail;
use Sentinel;
use DateTime;

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
        $employees = Employee::join('users','users.id','employees.user_id')->select('users.first_name','users.last_name','employees.*')->where('employees.id','<>',1)->where('checkout',null)->orderBy('users.first_name')->get();

        $reg = null;
        if( $request->get('reg')) {
            $reg = $request->get('reg');
        }      
        if( $request->get('car_id')) {
            $car_id = $request->get('car_id');
        } else {
            $car_id = null;
        }
        return view('Centaur::loccos.create', ['cars' => $cars, 'employees' => $employees, 'registracija' => $reg, 'car_id' => $car_id]);
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
			'travel_id'     => $request['travel_id'] ? $request['travel_id'] : null,
			'employee_id'   => $request['employee_id'],
			'date'  	    => $request['date'],
            'end_date'  	=> $request['end_date'] ? $request['end_date'] : null,
            'starting_point' => $request['starting_point'],
			'destination'   => $request['destination'],
			'start_km'  	=> $request['start_km'],
			'end_km'        => $request['end_km'] ? $request['end_km'] : null,
			'distance'      => $request['distance'] ? $request['distance'] : null,
			'comment'       => $request['comment']
		);
        if($request['end_km'] && $request['distance'] && $request['end_date']) {
            $data += ['status'  => 1];
        } else {
            $data += ['status'  => 0];
        }

		$locco = new Locco();
        $locco->saveLocco($data);
       
        /* Upis u evidenciju rada ako je otvoren putni nalog prije 8:15 / 7:00 */
        $now = new DateTime();
      
        $time = '08:00';
        
        $workRecord = WorkRecord::where('employee_id', $request['employee_id'])->whereDate('start', $now->format('Y-m-d'))->first();
        if( ! $workRecord ) {
            if( $now->format('Y-m-d H:i') < $now->format('Y-m-d ' . $time )) {
                $data = array(
                    'employee_id'  	=> $request['employee_id'],
                    'start'  		=>  $now->format('Y-m-d H:i'),
                );
                $workRecord = new WorkRecord();
                $workRecord->saveWorkRecords($data);
            }
        }

        /* Update Car - trenutni kilometri */
        if($request['end_km']) {
            $car = Car::find($request['car_id']);
            $data_car = array(
                'current_km'  => $request['end_km']
            );
            $car->updateCar($data_car);
        }
   
        if( $request['travel']) {
            $data_travel = array(
                'date'  		    => $request['date'],
                'employee_id'  	    => $request['employee_id'],
                'car_id'  		    => $request['car_id'],
                'destination'  	    => $request['destination'],
                'description'  	    => $request['comment'],
                'days'  	        => 1,
                'start_date'  	    => $request['date'],
                'end_date'  	    => null,
                'locco_id'  	    => $locco->id,
            );
            
            $travelOrder = new TravelOrder();
            $travelOrder->saveTravelOrder($data_travel);

            $data_locco = array(
                'travel_id'  => $travelOrder->id,
            );
            $locco->updateLocco($data_locco);
            
            /* mail obavijest o novom putnom nalogu */
            $send_to = EmailingController::sendTo('travel_orders','create');
            try {
                foreach(array_unique($send_to) as $send_to_mail) { // mailovi upisani u mailing 
                    if( $send_to_mail != null & $send_to_mail != '' ) {
                        Mail::to($send_to_mail)->send(new TravelCreate($travelOrder));  
                    }
                }
            } catch (\Throwable $th) {
                session()->flash('error', __('ctrl.data_save') . ', '. __('ctrl.email_error'));
                return redirect()->back();
            }
        }
      
        if($request['wrong_km']){
			if(! $request['comment'] ){
				$message = session()->flash('error', __('ctrl.wrong_km'));
				return redirect()->back()->withFlashMessage($message);
			} else {
                $send_to = EmailingController::sendTo('loccos','create');
                try {
                    foreach(array_unique($send_to) as $send_to_mail) {
                        if( $send_to_mail != null & $send_to_mail != '' ) {
                            Mail::to($send_to_mail)->send(new CarServiceMail($locco)); // mailovi upisani u mailing 
                        }
                    }
                } catch (\Throwable $th) {
                    session()->flash('error', __('ctrl.data_save') . ', '. __('ctrl.email_error'));
                    return redirect()->back();
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
    public function show($id, Request $request)
    {
        $car = Car::find($id);
        
        if(isset( $request['date'])) {
            $request_date = $request['date'];
        } else {
            $request_date = date('Y-m');
        }
        
        $loccos = Locco::where('car_id', $id)->whereYear('date', '>=', date('Y')-1)->get();
        $permission_dep = DashboardController::getDepartmentPermission();
       
        $dates = array();
        foreach (array_keys($loccos->groupBy('date')->toArray()) as $locco_date) {
            array_push($dates, date('Y-m',strtotime($locco_date)) );
        }
     
        $dates = array_unique($dates);
        rsort($dates);

        /* $loccos = Locco::where('car_id', $id)->whereYear('date', $year )->whereMonth('date',$month)->orderBy('date','ASC')->get(); */

        $loccos = $loccos->filter(function ($locco, $key) use ($request_date, $id) {
            return date('Y-m',strtotime( $locco->date)) == $request_date /* && $locco->car_id == $id */;
        });
       
        if( ! in_array($request_date, $dates)) {
            array_unshift($dates, $request_date);
        }
      
        return view('Centaur::loccos.show', ['loccos' => $loccos, 'car' =>  $car, 'dates' => $dates, 'permission_dep' => $permission_dep,'request_date' => $request_date]);
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
        $travel = TravelOrder::find( $locco->travel_id );
    
        $cars = Car::orderBy('registration','ASC')->get();
        $employees = Employee::join('users','users.id','employees.user_id')->select('users.first_name','users.last_name','employees.*')->where('employees.id','<>',1)->where('checkout',null)->orderBy('users.first_name')->get();

        return view('Centaur::loccos.edit', ['locco' => $locco, 'cars' => $cars, 'travel' => $travel, 'employees' => $employees]);
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
			'travel_id'     => $request['travel_id'] ? $request['travel_id'] : null,
			'employee_id'   => $request['employee_id'],
			'date'  	    => $request['date'],
            'end_date'  	=> $request['end_date'] ? $request['end_date'] : null,
            'starting_point'=> $request['starting_point'],
			'destination'   => $request['destination'],
			'start_km'  	=> $request['start_km'],
			'end_km'        => $request['end_km'] ? $request['end_km'] : null,
			'distance'      => $request['distance'] ? $request['distance'] : null,
			'comment'       => $request['comment']
            
		);
     
        if($request['end_km'] && $request['distance'] && $request['end_date']) {
            $data += ['status'  => 1];
        } else {
            $data += ['status'  => 0];
        }
        
        $locco->updateLocco($data);
       
        if($request['end_km']) {
            $car = Car::find($request['car_id']);
            $data_car = array(
                'current_km'  => $request['end_km']
            );
            $car->updateCar($data_car);
        }

        $begin = new DateTime( $request['date']);
        $end = new DateTime($request['end_date']);
        $brojDana = date_diff($end, $begin);

        $data_travel = array(
            'date'  		    => $request['date'],
            'employee_id'  	    => $request['employee_id'],
            'car_id'  		    => $request['car_id'],
            'destination'  	    => $request['destination'],
            'description'  	    => $request['comment'],
            'days'  	        => $brojDana->d + 1,
            'start_date'  	    => $request['date'],
            'end_date'  	    => $request['end_date'],
            'locco_id'  	    => $locco->id,
        );

        if($locco->travel_id) {
            $travelOrder = TravelOrder::find($locco->travel_id);

            if($travelOrder) {
                $travelOrder->updateTravelOrder($data_travel);
            } else {
                $travelOrder = new TravelOrder();
                $travelOrder->saveTravelOrder($data_travel);

                $data_locco = array(
                    'travel_id'  => $travelOrder->id,
                );
                $locco->updateLocco($data_locco);
                
             /*    $data_loccoTravel = array(
                    'travel_id'  => $travelOrder->id,
                    'starting_point'  =>  $request['starting_point'],
                    'destination'  =>  $request['km_destination'],
                    'distance'  =>  $request['distance'],
                );

                $travelLocco = new TravelLocco();
                $travelLocco->saveTravelLocco( $data_loccoTravel ); */
               
            }
        } else if( $request['travel']) {
            $travelOrder = new TravelOrder();
            $travelOrder->saveTravelOrder($data_travel);

            $data_locco = array(
                'travel_id'  => $travelOrder->id,
            );
            $locco->updateLocco($data_locco);

        /*     $data_loccoTravel = array(
                'travel_id'  => $travelOrder->id,
                'starting_point'  =>  $request['starting_point'],
                'destination'  =>  $request['km_destination'],
                'distance'  =>  $request['distance'],
            );

            $travelLocco = new TravelLocco();
            $travelLocco->saveTravelLocco( $data_loccoTravel ); */
        }
       
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
        $locco->delete();
        
        session()->flash('success',__('ctrl.data_delete'));
		
        return redirect()->back();
    }

    public function exportLoccos(Request $request) 
    {
        $godina = date('Y',strtotime( $request['date']));
        $mjesec = date('m',strtotime( $request['date']));

        $cars = Car::orderBy('registration','ASC')->get();

        return view('Centaur::loccos.loccos_all', ['cars' => $cars, 'month' => $request['date']]);
    }
}
