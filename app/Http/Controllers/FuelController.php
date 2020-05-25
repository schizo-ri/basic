<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Fuel;
use App\Models\Car;
use App\Models\Locco;
use Sentinel;
use DateTime;

class FuelController extends Controller
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
    public function index(Request $request)
    {
        $car_id = null;
        $empl = Sentinel::getUser()->employee;
		$permission_dep = array();
        
		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
        } 

        if( $request['car_id'] ) {
           
            $fuels = Fuel::where('car_id', $request['car_id'])->orderBy('date','DESC')->get();

            return view('Centaur::fuels.index', ['fuels' => $fuels, 'permission_dep' => $permission_dep]);
        } else {
            $fuels = Fuel::orderBy('date','DESC')->get();

            return view('Centaur::fuels.index', ['fuels' => $fuels, 'permission_dep' => $permission_dep]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $cars = Car::get();
        
        $car_id = null;

        if( $request['car_id'] ) {
            $car_id = $request['car_id'];
        }
		return view('Centaur::fuels.create', ['cars' => $cars,'car_id' => $car_id]);
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
			'employee_id'   => Sentinel::getUser()->employee->id,
			'liters'        => $request['liters'],
			'km'            => $request['km'],
			'date'          => $request['date'],
		);
		
		$fuel = new Fuel();
        $fuel->saveFuel($data);
        
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
        $fuel = Fuel::find($id);
        $cars = Car::get();

        return view('Centaur::fuels.edit', ['cars' => $cars,'fuel' => $fuel]);
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
        $fuel = Fuel::find($id);

        $data = array(
			'car_id'        => $request['car_id'],
			'employee_id'   => Sentinel::getUser()->employee->id,
			'liters'        => $request['liters'],
			'km'            => $request['km'],
			'date'          => $request['date'],
		);
		
        $fuel->updateFuel($data);
        
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
        $fuel = Fuel::find($id);
        $fuel->delete();

        session()->flash('success',__('ctrl.data_delete'));
		
        return redirect()->back();
    }
}
