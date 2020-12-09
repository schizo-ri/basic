<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Models\EnergyConsumption;
use App\Models\EnergyLocation;
use App\Models\EnergySource;
use Sentinel;

class EnergyConsumptionController extends Controller
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
        $energyConsumptions = EnergyConsumption::orderBy('location_id','ASC')->orderBy('energy_id','ASC')->orderBy('date','DESC')->get();

        $permission_dep = DashboardController::getDepartmentPermission();

		return view('Centaur::energy_consumptions.index', ['energyConsumptions' => $energyConsumptions, 'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $locations = EnergyLocation::orderBy('name','ASC')->get();
        $energySources = EnergySource::orderBy('name','ASC')->get();
        
        return view('Centaur::energy_consumptions.create', ['locations' => $locations, 'energySources' => $energySources ]);
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
            'energy_id'  	=> $request['energy_id'],
            'location_id'  	=> $request['location_id'],
            'date'          => $request['date'],
            'counter' 	    => $request['counter'],
            'comment' 	    => $request['comment'],
        );
        
        $energyConsumption = new EnergyConsumption();
        $energyConsumption->saveEnergyConsumption($data);
        
        session()->flash('success',  __('ctrl.data_save'));
        return redirect()->back();
    }

    public function lastCounter(  $location_id, $energy_id)
    {
        $counter = new EnergyConsumption();

        $counter =  $counter->lastCounter( $energy_id, $location_id);

        return $counter;
    }

    public function lastCounter_Skip(  $location_id, $energy_id)
    {
        $counter = new EnergyConsumption();

        $counter =  $counter->lastCounter_Skip( $energy_id, $location_id);

        return $counter;
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
        $energyConsumption = EnergyConsumption::find($id);

        $locations = EnergyLocation::orderBy('name','ASC')->get();
        $energySources = EnergySource::orderBy('name','ASC')->get();
        
        return view('Centaur::energy_consumptions.edit', ['energyConsumption' => $energyConsumption,'locations' => $locations, 'energySources' => $energySources ]);

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
        $energyConsumption = EnergyConsumption::find($id);

         $data = array(
            'energy_id'  	=> $request['energy_id'],
            'location_id'  	=> $request['location_id'],
            'date'          => $request['date'],
            'counter' 	    => $request['counter'],
            'comment' 	    => $request['comment'],
        );
        
        $energyConsumption->updateEnergyConsumption($data);
        
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
        $energyConsumption = EnergyConsumption::find($id);
        $energyConsumption->delete();

        session()->flash('success',__('ctrl.data_delete'));
		
        return redirect()->back();
    }
}
