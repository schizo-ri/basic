<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Locco;
use Sentinel;

class CarController extends Controller
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
        $loccos = Locco::get();
        $empl = Sentinel::getUser()->employee;
		$permission_dep = array();
        
		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
        } 

        return view('Centaur::cars.index', ['cars' => $cars, 'loccos' => $loccos, 'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::orderBy('name','ASC')->get();
        $employees = Employee::where('id','<>',1)->where('checkout',null)->get();

        return view('Centaur::cars.create',['departments' => $departments, 'employees' => $employees  ]);
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
			'manufacturer'      => $request['manufacturer'],
			'model'  	        => $request['model'],
			'registration'  	=> $request['registration'],
			'chassis'  	        => $request['chassis'],
			'first_registration'=> $request['first_registration'],
			'last_registration' => $request['last_registration'],
			'last_service'      => $request['last_service'],
            'current_km'        => $request['current_km'],
            'department_id'     => $request['department_id'],
            'employee_id'       => $request['employee_id'],
            
		);
		
		$car = new Car();
        $car->saveCar($data);
       
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
        $car = Car::find($id);
        $departments = Department::orderBy('name','ASC')->get();
        $employees = Employee::where('id','<>',1)->where('checkout',null)->get();

        return view('Centaur::cars.edit', ['car' => $car, 'departments' => $departments, 'employees' => $employees  ]);
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
        $car = Car::find($id);

        $data = array(
			'manufacturer'      => $request['manufacturer'],
			'model'  	        => $request['model'],
			'registration'  	=> $request['registration'],
			'chassis'  	        => $request['chassis'],
			'first_registration'=> $request['first_registration'],
			'last_registration' => $request['last_registration'],
			'last_service'      => $request['last_service'],
            'current_km'        => $request['current_km'],
            'department_id'     => $request['department_id'],
            'employee_id'       => $request['employee_id'],
            
		);
		        
        $car->updateCar($data);
       
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
        $car = Car::find($id);
        $car->delete();

        session()->flash('success',__('ctrl.data_delete'));
		
        return redirect()->back();
    }

    public function last_km(Request $request) 
    {
        $car = Car::find($request['car_id']);
        $current_km = $car->current_km;

        return $current_km;
    }
    
}
