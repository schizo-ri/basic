<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerLocation;

class CustomerLocationController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $customer = null;

        if( isset($request['customer_id'] )) {
            $customer = Customer::find($request['customer_id']);
        }
        return view('Centaur::customer_locations.create', ['customer' => $customer]);
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
			'customer_id'  	=> $request['customer_id'],
			'address'  		=> $request['address'],
			'city'  		=> $request['city'],
		);

		$customerLocation = new CustomerLocation();
        $customerLocation->saveCustomerLocation($data);
        
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
        $locations = null; 
        $customer = Customer::find($id);

        if( $customer ) {
            $locations =  $customer->hasLocations;
        }

        return view('Centaur::customer_locations.show', ['locations' => $locations, 'customer' => $customer]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer_location = CustomerLocation::find($id);

        return view('Centaur::customer_locations.edit', ['customer_location' => $customer_location]);
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
        $customer_location = CustomerLocation::find($id);

        $data = array(
			'address'  		=> $request['address'],
			'city'  		=> $request['city'],
		);

        $customer_location->updateCustomerLocation($data);
        
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
        $customer_location = CustomerLocation::find($id);
        $customer_location->delete();

        session()->flash('success',  __('ctrl.data_delete'));
		return redirect()->back();
    }

    public function getCustomerLocation (Request $request) 
    {
        $customer = Customer::find($request['customer_id']);
        $locations = $customer ? $customer->hasLocations : null;

        return $locations;
    }
}
