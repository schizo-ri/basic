<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Models\EnergyConsumption;
use App\Models\EnergyLocation;
use App\Models\EnergySource;
use Sentinel;

class EnergySourceController extends Controller
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
        $energySources = EnergySource::get();

        $permission_dep = DashboardController::getDepartmentPermission();

		return view('Centaur::energy_sources.index', ['energySources' => $energySources, 'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Centaur::energy_sources.create');
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
            'name'  	=> $request['name'],
            'comment' 	=> $request['comment'],
        );
        
        $energySource = new EnergySource();
        $energySource->saveEnergySource($data);
        
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
        $energySource = EnergySource::find( $id );

        return view('Centaur::energy_sources.edit',['energySource' => $energySource ] );
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
        $energySource = EnergySource::find( $id );
        $data = array(
            'name'  	=> $request['name'],
            'comment' 	=> $request['comment'],
        );
        
        $energySource->updateEnergySource($data);
        
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
        $energySource = EnergySource::find( $id );
        $energySource->delete();

        session()->flash('success',__('ctrl.data_delete'));
		
        return redirect()->back();
    }
}