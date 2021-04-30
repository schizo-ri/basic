<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ContractSubject;
use App\Models\Customer;
use App\Models\CustomerLocation;
use App\Models\Contract;

class ContractSubjectController extends Controller
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
        $contract_subjects = ContractSubject::orderBy('date','DESC')->get();

		return view('Centaur::contract_subjects.index', ['contract_subjects' => $contract_subjects]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $contract_id = null;
        if (isset($request['contract_id'])) {
            $contract_id = $request['contract_id'];
        }
        $customers = Customer::where('active',1)->get();
        $customer_locations = CustomerLocation::get();
        $contracts = Contract::orderBy('date','DESC')->get();

        return view('Centaur::contract_subjects.create', ['contracts' => $contracts, 'customers' => $customers,'contract_id' => $contract_id,'customer_locations' => $customer_locations]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if( $request['customer_name']) {
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
        } 

        $data = array(
			'contract_id'  	=> $request['contract_id'],
			'location_id'  	=> isset($customerLocation) ? $customerLocation->id : $request['location_id'],
			'name'  		=> $request['name'],
			'serial_no'  	=> $request['serial_no'],
			'counter_bw'    => $request['counter_bw'],
			'counter_c'     => $request['counter_c'],
			'flat_rate'     => $request['flat_rate'],
            'no_prints_bw'  => $request['no_prints_bw'],
			'no_prints_c'   => $request['no_prints_c'],
            'price_a4_bw'   => $request['price_a4_bw'],
            'price_a4_c'    => $request['price_a4_c'],
			
		);
     
		$contractSubject = new ContractSubject();
        $contractSubject->saveContractSubject($data);
        
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
        $contract_subject = ContractSubject::find($id);
        $customers = Customer::where('active',1)->get();
        $customer_locations = CustomerLocation::get();
        $contracts = Contract::orderBy('date','DESC')->get();

        return view('Centaur::contract_subjects.edit', ['contracts' => $contracts, 'customers' => $customers,'contract_subject' => $contract_subject,'customer_locations' => $customer_locations]);
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
        $contract_subject = ContractSubject::find($id);

        if( $request['customer_name']) {
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
        }

        $data = array(
			'contract_id'  	=> $request['contract_id'],
			'location_id'  	=> isset($customerLocation) ? $customerLocation->id : $request['location_id'],
			'name'  		=> $request['name'],
			'serial_no'  	=> $request['serial_no'],
			'counter_bw'    => $request['counter_bw'],
			'counter_c'     => $request['counter_c'],
			'flat_rate'     => $request['flat_rate'],
            'no_prints_bw'  => $request['no_prints_bw'],
			'no_prints_c'   => $request['no_prints_c'],
            'price_a4_bw'   => $request['price_a4_bw'],
            'price_a4_c'    => $request['price_a4_c'],
		);
     
        $contractSubject->updateContractSubject($data);
        
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
        $contract_subject = ContractSubject::find($id);
        $contract_subject->delete();

        session()->flash('success',  __('ctrl.data_delete'));
		return redirect()->back();
    }
}
