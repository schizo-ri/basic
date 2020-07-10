<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Travelrequest;
use App\Http\Controllers\Controller;
use App\Models\TravelOrder;
use App\Models\Car;
use App\Models\Employee;
use App\Models\Locco;
use App\Models\Company;
use App\Models\TravelExpense;
use App\Models\TravelLocco;
use Sentinel;
use PDF;
use App;

class TravelOrderController extends Controller
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
        $travel_orders = TravelOrder::get();

        if(isset($request['date']) && $request['date'] != 'all') {
            $travel_orders = TravelOrder::where('date','like', $request['date'].'%')->get();
        } 
        if(isset($request['employee_id']) &&  $request['employee_id'] != 'all') {
            $travel_orders = $travel_orders->where('employee_id',$request['employee_id'] );
        } 
    
        $employees = Employee::join('users','users.id','employees.user_id')->select('employees.*','users.first_name', 'users.last_name')->where('employees.checkout',null)->where('employees.id','<>',1)->get();
       
        $dates = array();
        foreach (array_keys(  $travel_orders->groupBy('date')->toArray()) as $date) {
            array_push($dates, date('Y-m',strtotime($date)) );
        }
        $dates = array_unique($dates);

        $empl = Sentinel::getUser()->employee;
		$permission_dep = array();
        
		if($empl) {
			$permission_dep = explode(',', count($empl->work->department->departmentRole) > 0 ? $empl->work->department->departmentRole->toArray()[0]['permissions'] : '');
        } 

        return view('Centaur::travel_orders.index', ['travel_orders' => $travel_orders,'employees' => $employees,'dates' => $dates,'test1' =>$request['employee_id'],'test2' => $request['date'], 'permission_dep' => $permission_dep]);
    }

    public function travelFilter (Request $request) 
    {
        return $request['id'];
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cars = Car::orderBy('registration','ASC')->get();
        $employees = Employee::where('id','<>',1)->where('checkout',null)->get();
        $loccos = Locco::orderBy('date','DESC')->get();

        return view('Centaur::travel_orders.create', ['cars' => $cars, 'loccos' => $loccos,'employees' => $employees]);
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
            'date'  		    => $request['date'],
            'employee_id'  	    => $request['employee_id'],
            'car_id'  		    => $request['car_id'],
            'destination'  	    => $request['destination'],
            'days'  	        => $request['days'] ? $request['days'] : 1,
            'start_date'  	    => $request['start_date'],
            'end_date'  	    => $request['end_date'],
            'advance'  	        => $request['advance'] ? $request['advance'] : 0,
            'advance_date'      => $request['advance_date'],
            'rest_payout'  	    => $request['rest_payout'] ? $request['rest_payout'] : 0,
            'calculate_employee'  => $request['calculate_employee'],
            'locco_id'  	    => $request['locco_id'],
        );
       
        $travelOrder = new TravelOrder();
        $travelOrder->saveTravelOrder($data);
        
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
        $travel_orders = TravelOrder::where('employee_id', $id )->where('status',0)->get();
    
        return view('Centaur::travel_orders.show', ['travel_orders' => $travel_orders]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $travel = TravelOrder::find($id);

        $cars = Car::orderBy('registration','ASC')->get();
        $employees = Employee::where('id','<>',1)->where('checkout',null)->get();
        $loccos = Locco::orderBy('date','DESC')->get();

        return view('Centaur::travel_orders.edit', ['travel' => $travel, 'cars' => $cars, 'loccos' => $loccos,'employees' => $employees]);
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
    
        $travel = TravelOrder::find($id);
        
        $data = array(
            'date'  		    => $request['start_date'],
            'employee_id'  	    => $request['employee_id'],
            'car_id'  		    => $request['car_id'],
            'destination'  	    => $request['destination'],
            'description'  	    => $request['description'],
            'days'  	        => $request['days'],
            'start_date'  	    => $request['start_date'],
            'end_date'  	    => $request['end_date'],
            'advance'  	        => $request['advance'],
            'advance_date'      => $request['advance_date'],
            'rest_payout'  	    => $request['rest_payout'],
            'calculate_employee' => $request['calculate_employee'],
         /*    'locco_id'  	    => $request['locco_id'], */
        );

        $travel->saveTravelOrder($data);
        
        // Ostali troškovi
        $data_expenses = array();
       
        if(isset($request['bill'])) {
            foreach ($request['bill'] as $key => $bill) {
                if( $bill != null ) {
                    $cost_description = $request['cost_description'][$key];
                    $amount = $request['amount'][$key];
                    $currency = $request['currency'][$key];
                    $total_amount = $request['total_amount'][$key];
                    $travel_id = $travel->id;
                    $data_expenses += ['bill' => $bill];
                    $data_expenses += ['cost_description' => $cost_description];
                    $data_expenses += ['amount' => $amount];
                    $data_expenses += ['currency' => $currency];
                    $data_expenses += ['total_amount' => $total_amount];
                    $data_expenses += ['travel_id' =>  $travel->id];
                    
                    if(isset($request['expence_id'][$key])) {
                        $travelExpense = TravelExpense::find($request['expence_id'][$key]);
                        $travelExpense->updateTravelExpense($data_expenses);
                    } else {
                        $travelExpense = new TravelExpense();
                        $travelExpense->saveTravelExpense($data_expenses);
                    }
                    $data_expenses = array();
                }
            }
        }
        // Kilometraža
        $data_locco = array($request);
        if(isset($request['starting_point'])) {
            foreach ($request['starting_point'] as $key => $starting_point) {
                if( $starting_point != null ) {
                    
                    $km_destination = $request['km_destination'][$key];
                    $distance = $request['distance'][$key];

                    $data_locco += ['starting_point' => $starting_point];
                    $data_locco += ['destination' => $km_destination];
                    $data_locco += ['distance' => $distance];
                    $data_locco += ['travel_id' =>  $travel->id];

                    if(isset($request['locco_id'][$key])) {
                        $travelLocco = TravelLocco::find($request['locco_id'][$key]);
                        $travelLocco->updateTravelLocco($data_locco);
                    } else {
                        $travelLocco = new TravelLocco();
                        $travelLocco->saveTravelLocco($data_locco);
                    }

                    $data_locco = array();
                }
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
        $travel = TravelOrder::find($id);
        $travel->delete();

        session()->flash('success',__('ctrl.data_delete'));
		
        return redirect()->back();
    }

    public function close_travel(Request $request)
    {
        $travel = TravelOrder::find($request['id']);

        if( $travel->status == 0 || $travel->status == null ) {
            $status = 1;
            $message = __('basic.order_closed');
            $this->pdfTravel($travel->id);
        } else {
            $status = 0;
            $message = __('basic.order_open');
          
        }

        $data = array(
            'status'  		    => $status,
        );

        $travel->saveTravelOrder($data);

        $this->pdfTravel($travel->id);
        
        return $message;
    }

    public function travelShow ($id) 
    {
       $travel = TravelOrder::find($id);
       $company = Company::first();
       $cars = Car::orderBy('registration','ASC')->get();
       $employees = Employee::where('id','<>',1)->where('checkout',null)->get();
       $locco = $travel->locco; // locco prema zapisanom id u travel
     
       $loccos = $travel->loccos; // locco prema zapisanom id u travel
       
       return view('Centaur::travel_orders.travelShow',['travel' => $travel, 'company' => $company, 'cars' => $cars, 'locco' => $locco,'loccos' => $loccos,'employees' => $employees ]);
    }

     /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public static function pdfTravel($id)
    {
        $travel = TravelOrder::find($id);
        $company = Company::first();
    
        $data = [
            'travel' =>  $travel,
            'company' => $company
        ];
        $pdf = PDF::loadView('Centaur::travel_orders.travel_pdf', $data);  

        $path = '../public/travelOrder/';
        if (!file_exists($path)) {
          mkdir($path);
        }

        $pdf->save($path.'Putni nalog_'.$travel->id.'.pdf');
        //   return $pdf->download('Putni nalog.pdf');

        return true;

    }
}
