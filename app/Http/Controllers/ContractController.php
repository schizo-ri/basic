<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Customer;
use App\Models\ContractTemplate;
use App\Models\ContractSubject;
use App\Models\CustomerLocation;
use App\Models\Company;

class ContractController extends Controller
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
        $contracts = Contract::get();
       
		return view('Centaur::contracts.index', ['contracts' => $contracts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->where('active',1)->with('hasLocations')->get();
        $templates =  ContractTemplate::orderBy('name','ASC')->get();

        return view('Centaur::contracts.create', ['customers' => $customers,'templates' => $templates]);
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
			'template_id'  	    => $request['template_id'],
			'customer_id'  	    => isset( $customer ) ? $customer->id : $request['customer_id'],
			'contract_no'   	=> $request['contract_no'],
			'name'  		    => $request['name'],
			'date'  		    => $request['date'],
			'duration'          => $request['duration'],
			'invoice_no'        => $request['invoice_no'],
			'invoice_date'      => $request['invoice_date'],
            'package_prints_bw' => $request['package_prints_bw'],
			'package_prints_c'  => $request['package_prints_c'],
			'debenture_amount'  => $request['debenture_amount'],
		);
     
		$contract = new Contract();
        $contract->saveContract($data);
   
        if( $request['subject_name'] ) {
            foreach ($request['subject_name'] as $key => $subject_name) {
                if( $request['subject_location_address'] [$key ]) {
                    $data_location = array(
                        'customer_id'  	=> $request['customer_id'],
                        'address'  		=> $request['subject_location_address'] [$key ],
                        'city'  		=> $request['subject_location_city'] [$key ],
                    );
                   
                    $customerLocation = new CustomerLocation();
                    $customerLocation->saveCustomerLocation($data_location);
                }
                $data_subject = array(
                    'contract_id'   => $contract->id,
                    'location_id'   =>  isset($customerLocation) ? $customerLocation->id : $request['subject_location_id'][$key],
                    'name'          => $subject_name,
                    'serial_no'     => $request['subject_serial_no'][$key],
                    'counter_bw'    => $request['subject_counter_bw'][$key],
                    'counter_c'     => $request['subject_counter_c'][$key],
                    'flat_rate'     => $request['subject_flat_rate'][$key],
                    'price_a4_bw'   => $request['subject_price_a4_bw'][$key],
                    'price_a4_c'    => $request['subject_price_a4_c'][$key],
                    'no_prints_bw'  => $request['subject_no_prints_bw'][$key],
                    'no_prints_c'   => $request['subject_no_prints_c'][$key],
                );

                $contractSubject = new ContractSubject();
                $contractSubject->saveContractSubject($data_subject);
   
            }
        }
        
        session()->flash('success',  __('ctrl.data_save'));
		return redirect()->route('contracts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contract = Contract::find($id);
        $company = Company::first();

        return view('Centaur::contracts.show', ['contract' => $contract, 'company' => $company]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contract = Contract::find($id);
        $customers = Customer::orderBy('name')->where('active',1)->with('hasLocations')->get();
        $templates =  ContractTemplate::orderBy('name','ASC')->get();

        return view('Centaur::contracts.edit', ['contract' => $contract, 'templates' => $templates, 'customers' => $customers]);
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
        $contract = Contract::find($id);

        $data = array(
			'template_id'  	    => $request['template_id'],
			'customer_id'  	    => isset( $customer ) ? $customer->id : $request['customer_id'],
			'contract_no'   	=> $request['contract_no'],
			'name'  		    => $request['name'],
			'date'  		    => $request['date'],
			'duration'          => $request['duration'],
			'invoice_no'        => $request['invoice_no'],
			'invoice_date'      => $request['invoice_date'],
            'package_prints_bw' => $request['package_prints_bw'],
			'package_prints_c'  => $request['package_prints_c'],
			'debenture_amount'  => $request['debenture_amount'],
		);
      
        $contract->updateContract($data);
        
        if( $request['subject_name'] ) {
            foreach ($request['subject_name'] as $key => $subject_name) {
               

                if( $request['subject_location_address'] [$key ]) {
                    $data_location = array(
                        'customer_id'  	=> $request['customer_id'],
                        'address'  		=> $request['subject_location_address'] [$key ],
                        'city'  		=> $request['subject_location_city'] [$key ],
                    );
                   
                    $customerLocation = new CustomerLocation();
                    $customerLocation->saveCustomerLocation($data_location);
                }
                $data_subject = array(
                    'contract_id'   => $contract->id,
                    'location_id'   => isset($customerLocation) ? $customerLocation->id : $request['subject_location_id'][$key],
                    'name'          => $subject_name,
                    'serial_no'     => $request['subject_serial_no'][$key],
                    'counter_bw'    => $request['subject_counter_bw'][$key],
                    'counter_c'     => $request['subject_counter_c'][$key],
                    'flat_rate'     => $request['subject_flat_rate'][$key],
                    'price_a4_bw'   => $request['subject_price_a4_bw'][$key],
                    'price_a4_c'    => $request['subject_price_a4_c'][$key],
                    'no_prints_bw'  => $request['subject_no_prints_bw'][$key],
                    'no_prints_c'   => $request['subject_no_prints_c'][$key],
                );
                if ( isset($request['subject_id'][$key])) {
                    $contractSubject = ContractSubject::find($request['subject_id'][$key]);
                    $contractSubject->updateContractSubject($data_subject);
                } else {
                    $contractSubject = new ContractSubject();
                    $contractSubject->saveContractSubject($data_subject);
                }
            }
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
        $contract = Contract::find($id);
        if( $contract ) {
            if( count( $contract->hasSubjects ) >0 ) {
                foreach($contract->hasSubjects as $subject) {
                    $subject->delete();
                }
            }

            $contract->delete();
            session()->flash('success',  __('ctrl.data_delete'));
            return redirect()->back();
        } else {
            session()->flash('success',  __('ctrl.no_data'));
            return redirect()->back();
        }
    }

    public function getConctract (Request $request)
    {
        $contract = Contract::with('hasSubjects')->find($request['id']);
        $location_ids = $contract->hasSubjects->pluck('location_id')->toArray();

        $locations = CustomerLocation::whereIn('id', $location_ids)->get();
        $data = array();
        $data['contract'] = $contract;
        $data['locations'] = $locations;
        
        return $data;
    }
}
