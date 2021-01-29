<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Models\Fuel;
use App\Models\Car;
use App\Models\Locco;
use Sentinel;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FuelImport;
use DateTime;
use App\Mail\ErrorMail;
use Illuminate\Support\Facades\Mail;

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
        $permission_dep = DashboardController::getDepartmentPermission();
   
        /* $cars = Car::get('registration'); */
        $cars = Car::orderBy('registration', 'ASC')->get();
        $fuels = Fuel::orderBy('date','DESC')->get();
        $prev_fuels =  $fuels;
        $dates = array();
        foreach (array_keys($fuels->groupBy('date')->toArray()) as $date) {
            array_push($dates, date('Y-m',strtotime($date)) );
        }
        $dates = array_unique($dates);
        rsort($dates);

        if(isset( $request['date'] )  ) {
            $date = $request['date'];
        } else {
            $date = date('Y-m');
        }
        if(  $date != null &&  $date != 'null') {
            $fuels = $fuels->filter(function ($fuel, $key) use ($date) {
                return date('Y-m',strtotime( $fuel->date)) == $date /* && $locco->car_id == $id */;
            });
        }

        if( isset( $request['car']) && $request['car'] != null && $request['car'] != 'null') {
            $car_id =  $request['car'];
            $fuels = $fuels->filter(function ($fuel, $key) use ( $car_id ) {
                return $fuel->car_id == $car_id;
            });
        } 

        return view('Centaur::fuels.index', ['fuels' => $fuels,'prev_fuels' => $prev_fuels,'cars' => $cars, 'dates' => array_unique($dates), 'permission_dep' => $permission_dep]);
    
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
        $empl = Sentinel::getUser()->employee;
		$permission_dep = array();
        
		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
        } 

        $fuels = Fuel::where('car_id', $id)->orderBy('date','DESC')->get();

        return view('Centaur::fuels.show', ['fuels' => $fuels, 'permission_dep' => $permission_dep]);
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

    public function importFuel ()
    {
        try {
            Excel::import(new FuelImport, request()->file('file'));
            
            session()->flash('success',  __('ctrl.uploaded'));
            return redirect()->back();

        } catch (Throwable $th) {
            $email = 'jelena.juras@duplico.hr';
            $url = $_SERVER['REQUEST_URI'];
            Mail::to($email)->send(new ErrorMail( $th->getFile() . ' => ' . $th->getMessage(), $url)); 

            session()->flash('error',  __('ctrl.file_error'));
            return redirect()->back();
        }  
    }
}
