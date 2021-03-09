<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VehicalService;
use App\Models\Car;
use Sentinel;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\VehicalServiceImport;
use Illuminate\Support\Facades\Mail;
use App\Mail\ErrorMail;
use DateTime;

class VehicalServiceController extends Controller
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
        $empl = Sentinel::getUser()->employee;
		$permission_dep = array();
        
		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
        } 
        $vehicalServices = VehicalService::orderBy('date','DESC')->get();
        $cars = Car::orderBy('registration', 'ASC')->get();
        $dates = array();
        foreach (array_keys($vehicalServices->groupBy('date')->toArray()) as $date1) {
            array_push($dates, date('Y',strtotime($date1)) );
        }
        $dates = array_unique($dates);
        rsort($dates);
        
        if(isset( $request['date'] ) && $request['date'] != null) {
            $date = $request['date'];
        } else {
            $date = null;
        }
        if(  $date != null &&  $date != 'null') {
            $vehicalServices = $vehicalServices->filter(function ($vehicalService, $key) use ($date) {
                return date('Y',strtotime( $vehicalService->date )) == $date;
            });
        }
       /*  dd($vehicalServices); */
        if( isset( $request['car']) && $request['car'] != null && $request['car'] != 'null') {
            $car_id =  $request['car'];
            $vehicalServices = $vehicalServices->filter(function ($vehicalService, $key) use ( $car_id ) {
                return $vehicalService->car_id == $car_id;
            });
        } 
     
        return view('Centaur::vehical_services.index', ['vehicalServices' => $vehicalServices, 'cars' => $cars,'dates' => array_unique($dates),'permission_dep' => $permission_dep]);
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
		return view('Centaur::vehical_services.create', ['cars' => $cars,'car_id' => $car_id]);
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
			'price'         => $request['price'],
			'km'            => $request['km'],
			'date'          => $request['date'],
			'comment'       => $request['comment'],
		);
		
		$vehical_service = new VehicalService();
        $vehical_service->saveVehicalService($data);
        
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

      
        $vehicalServices = VehicalService::where('car_id',  $id)->orderBy('date','DESC')->get();

        return view('Centaur::vehical_services.show', ['vehicalServices' => $vehicalServices, 'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vehical_service = VehicalService::find($id);
        $cars = Car::get();

        return view('Centaur::vehical_services.edit', ['cars' => $cars,'vehical_service' => $vehical_service]);
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
        $vehical_service = VehicalService::find($id);

        $data = array(
			'car_id'        => $request['car_id'],
			'employee_id'   => Sentinel::getUser()->employee->id,
			'price'        => $request['price'],
			'km'            => $request['km'],
			'date'          => $request['date'],
			'comment'          => $request['comment'],
		);
		
        $vehical_service->updateVehicalService($data);
        
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
        $vehical_service = VehicalService::find($id);
        $vehical_service->delete();

        session()->flash('success',__('ctrl.data_delete'));		
        return redirect()->back();

    }

    public function importService ()
    {
        try {
            Excel::import(new VehicalServiceImport, request()->file('file'));
            
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
