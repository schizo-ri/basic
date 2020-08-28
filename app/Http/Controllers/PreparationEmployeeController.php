<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PreparationEmployee;

class PreparationEmployeeController extends Controller
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
        //
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
        $preparation_employee = PreparationEmployee::where('preparation_id', $request['preparation_id'] )->get();

        if( count( $preparation_employee ) > 0 ) {
            foreach ($preparation_employee as $employee) {
                $employee->delete();
            }
        }
        
        if(isset($request['user_id'])) {
            if(count($request['user_id']) > 0 ) {
                
              
                foreach ($request['user_id'] as $user_id) {
                    $data = array(
                        'preparation_id'  => $request['preparation_id'],
                        'user_id'         => $user_id,
                    );
        
                    $preparation_employee = new PreparationEmployee();
                    $preparation_employee->savePreparationEmployee($data);
                }
            }
        }
        
        session()->flash('success', "Podaci su upisani");
        
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
        //
    }
}
