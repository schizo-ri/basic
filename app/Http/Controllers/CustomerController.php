<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerLocation;

class CustomerController extends Controller
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
        $customers = Customer::orderBy('name','ASC')->where('active',1)->get();

		return view('Centaur::customers.index', ['customers' => $customers]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Centaur::customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {
        if( $request['customer_name']) {
            //unos iz ugovora
            $data_customer = array(
                'name'  		=> $request['customer_name'],
                'address'  		=> $request['customer_address'],
                'city'  		=> $request['customer_city'],
                'oib'  			=> $request['customer_oib'],
                'active'        => 1,
                'representedBy' => $request['customer_representedBy'],
            );
            
            $customer = new Customer();
            $customer->saveCustomer($data_customer);

            $data_location = array(
                'customer_id'  	=> $customer->id,
                'address'  		=> $customer->address,
                'city'  		=> $customer->city,
            );
           
            $customerLocation = new CustomerLocation();
            $customerLocation->saveCustomerLocation($data_location);
            
            $data = array();
            $data['customer_id'] =  $customer->id;
            $data['locations'] =  $customer->hasLocations;

            return $data;
            /* session()->flash('success',  __('ctrl.data_save'));
            return redirect()->route('contracts.index'); */
        } else {
            $data = array(
                'name'  		=> $request['name'],
                'address'  		=> $request['address'],
                'city'  		=> $request['city'],
                'oib'  			=> $request['oib'],
                'active'        => isset($request['active']) ? $request['active'] : 1,
                'representedBy' => $request['representedBy'],
            );
            
            $customer = new Customer();
            $customer->saveCustomer($data);
           
            if( $request['location']) {
                $data = array(
                    'customer_id'  	=> $customer->id,
                    'address'  		=> $customer->address,
                    'city'  		=> $customer->city,
                );
               
                $customerLocation = new CustomerLocation();
                $customerLocation->saveCustomerLocation($data);
            }
          
            session()->flash('success',  __('ctrl.data_save'));
            return redirect()->back();
        }
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
        $customer = Customer::find($id);

        return view('Centaur::customers.edit', ['customer' => $customer]);
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
        $customer = Customer::find($id);

        $data = array(
			'name'  		=> $request['name'],
			'address'  		=> $request['address'],
			'city'  		=> $request['city'],
			'oib'  			=> $request['oib'],
			'active'        => $request['active'],
            'representedBy' => $request['representedBy'],
		);
		
        $customer->updateCustomer($data);

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
        $customer = Customer::find($id);
        $customer->delete();

        $message = session()->flash('success',  __('ctrl.data_delete'));
		
		return redirect()->back()->withFlashMessage($message);
    }
}
