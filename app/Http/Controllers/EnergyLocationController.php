<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DashboardController;
use App\Models\EnergyLocation;
use Sentinel;

class EnergyLocationController extends Controller
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
        $energyLocations = EnergyLocation::get();

        $permission_dep = DashboardController::getDepartmentPermission();

		return view('Centaur::energy_locations.index', ['energyLocations' => $energyLocations, 'permission_dep' => $permission_dep]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Centaur::energy_locations.create');
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
            'address'  	=> $request['address'],
            'city'      => $request['city'],
            'phone' 	=> $request['phone'],
            'comment' 	=> $request['comment'],
        );
        
        $energyLocation = new EnergyLocation();
        $energyLocation->saveEnergyLocation($data);
        
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
        $energyLocation = EnergyLocation::find($id);
        return view('Centaur::energy_locations.edit',['energyLocation' => $energyLocation]);
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
        $energyLocation = EnergyLocation::find($id);

        $data = array(
            'name'  	=> $request['name'],
            'address'  	=> $request['address'],
            'city'      => $request['city'],
            'phone' 	=> $request['phone'],
            'comment' 	=> $request['comment'],
        );
        
        $energyLocation->updateEnergyLocation($data);
        
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
        $energyLocation = EnergyLocation::find($id);
        $energyLocation->delete();

        session()->flash('success',__('ctrl.data_delete'));
		
        return redirect()->back();
    }
}
