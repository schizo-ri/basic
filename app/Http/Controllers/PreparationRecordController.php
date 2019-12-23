<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PreparationRecord;

class PreparationRecordController extends Controller
{
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
            'preparation_id'    => $request['preparation_id'],
            'preparation'       => $request['preparation'],
            'mechanical_processing'  => $request['mechanical_processing'],
            'date'              => date('Y-m-d'),
        );
      
        $preparationRecord = new PreparationRecord();
        $preparationRecord->savePreparationRecord($data);
        
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
        //
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
        $preparationRecord = PreparationRecord::find($id);

        $data = array(
            'preparation'       => $request['preparation'],
            'mechanical_processing'  => $request['mechanical_processing']
        );

        $preparationRecord->updatePreparationRecord($data);

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
        $preparationRecord = PreparationRecord::find($id);
        $preparationRecord->delete();
        
        session()->flash('success', "Podaci su obrisani");
        
        return redirect()->back();
    }
}
