<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Preparation;
use App\Models\EquipmentList;
use App\User;
use App\Models\PreparationRecord;
use App\Imports\EquipmentImport;
use Maatwebsite\Excel\Facades\Excel;

class PreparationController extends Controller
{
    public function __construct()
    {
        // Middleware
        $this->middleware('sentinel.auth');       
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $preparations = Preparation::orderBy('created_at','ASC')->get();
        $preparationRecords = PreparationRecord::orderBy('created_at','DESC')->get();
        $equipmentLists = EquipmentList::get();
        $users = User::orderBy('first_name','ASC')->get();

        return view('Centaur::preparations.index', ['users' => $users,'preparations' => $preparations, 'preparationRecords' => $preparationRecords, 'equipmentLists' => $equipmentLists]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'name' => $request['name'],
            'project_no'  => $request['project_no'],
            'project_manager'  => $request['project_manager'],
            'designed_by'  => $request['designed_by'],
            'preparation'  => $request['preparation'],
            'mechanical_processing'  => $request['mechanical_processing'],
            'delivery'  => $request['delivery'],
        );
      
        $preparation = new Preparation();
        $preparation->savePreparation($data);
        
        if( $request['preparation'] || $request['mechanical_processing']) {
            $data = array(
                'preparation_id'  => $preparation->id,
                'preparation'  => $request['preparation'],
                'mechanical_processing'  => $request['mechanical_processing'],
                'date'  => date('Y-m-d'),
            );
          
            $preparationRecord = new PreparationRecord();
            $preparationRecord->savePreparationRecord($data);
        }
        
        if(request()->file('file')) {
            try {
                Excel::import(new EquipmentImport, request()->file('file'));
            } catch (\Throwable $th) {
                session()->flash('error', "Došlo je do problema, dokument nije učitan!");
            
                return redirect()->back();
            }
        }
       

        session()->flash('success', "Podaci su spremljeni");
        
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
        $preparation = Preparation::find($id);
        
        $data = array(
            'name' => $request['name'],
            'project_no'  => $request['project_no'],
            'project_manager'  => $request['project_manager'],
            'designed_by'  => $request['designed_by'],
            'preparation'  => $request['preparation'],
            'mechanical_processing'  => $request['mechanical_processing'],
            'delivery'  => $request['delivery'],
        );
        $preparation->updatePreparation($data);

        $today = date('Y-m-d');

        if( $request['preparation'] || $request['mechanical_processing']) {            
            $data = array(
                'preparation_id'  => $preparation->id,
                'preparation'  => $request['preparation'],
                'mechanical_processing'  => $request['mechanical_processing'],
                'date'  => date('Y-m-d'),
            );
            
            $preparationRecord = PreparationRecord::where('preparation_id', $preparation->id)->whereDate('created_at', $today)->first();

            if($preparationRecord ) {
                $preparationRecord->updatePreparationRecord($data);
            } else {
                $preparationRecord = new PreparationRecord();
                $preparationRecord->savePreparationRecord($data);
            }           
        }

        session()->flash('success', "Podaci su ispravljeni");
        
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
        $preparation = Preparation::find($id);
        $preparation->delete();

        $preparationRecords = PreparationRecord::where('preparation_id', $preparation->id)->get();
        foreach ($preparationRecords as $preparationRecord ) {
            $preparationRecord->delete();
        }
        $equipments = EquipmentList::where('preparation_id', $preparation->id)->get();
        foreach ($equipments as $equipment ) {
            $equipment->delete();
        }

        session()->flash('success', "Podaci su obrisani");
        
        return redirect()->back();
    }
}
