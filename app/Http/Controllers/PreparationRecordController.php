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
            'marks_documentation'   => $request['marks_documentation'],
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

        $preparation_val = array();
        $marks_val = array();
        $mehan_val = array();
        foreach ($request['preparation_title'] as $key_title => $title) {
            foreach ($request['preparation'] as $key_value => $value) {
                if($key_title == $key_value) {
                    $preparation_val += [$title =>  $value];
                }
            }
        }

        foreach ($request['mechanical_title'] as $key_title => $title) {
            foreach ($request['mechanical_processing'] as $key_value => $value) {
                if($key_title == $key_value) {
                    $mehan_val += [$title =>  $value];
                }
            }
        }

        foreach ($request['marks_title'] as $key_title => $title) {
            foreach ($request['marks_documentation'] as $key_value => $value) {
                if($key_title == $key_value) {
                    $marks_val += [$title =>  $value];
                }
            }
        }

        $data = array(
            'preparation'  => json_encode($preparation_val ),
            'mechanical_processing'  => json_encode($mehan_val ),
            'marks_documentation'   => json_encode($marks_val ),
          
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
