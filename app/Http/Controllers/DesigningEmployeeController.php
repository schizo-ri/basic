<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DesigningEmployee;
use App\Models\Designing;
use Cartalyst\Sentinel\Users\EloquentUser;

class DesigningEmployeeController extends Controller
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
    public function create(Request $request)
    {
        $employees_designins = EloquentUser::whereHas('roles', function ($query) {
            return $query->where('slug', 'projektant');
        })->orderBy('first_name','ASC')->with('designins')->get();

        $designings = Designing::orderBy('created_at','ASC')->where('active', 1)->get();
        if( isset($request['user_id'])) {
            $user_id = $request['user_id'];
        } else {
            $user_id = null;
        }
        if( isset($request['start_date'])) {
            $start_date = $request['start_date'];
        } else {
            $start_date = null;
        }

        return view('Centaur::designing_employees.create', ['designings' => $designings, 'employees_designins' => $employees_designins,'user_id' => $user_id,'start_date' => $start_date,  ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $designing = Designing::find($request['designing_id']);

        if( ! $designing->designer_id ) {
            $data_designing = array(
                'designer_id'   => $request['user_id']
            );
            $designing->updateDesigning( $data_designing );
        }

        $data = array(
            'user_id'       => $request['user_id'],
            'designing_id'  => $request['designing_id'],
            'start_date'    => $request['start_date']
        );

        $designingEmployee = new DesigningEmployee();
        $designingEmployee->saveDesigningEmployee( $data );
     
       /*  return 'Podaci su spremljni'; */
        session()->flash('success', 'Podaci su spremljni');
		
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
        $designingEmployee = DesigningEmployee::find($id);
        $employees_designins = EloquentUser::whereHas('roles', function ($query) {
            return $query->where('slug', 'projektant');
        })->orderBy('first_name','ASC')->with('designins')->get();

        $designings = Designing::orderBy('created_at','ASC')->where('active', 1)->get();

        return view('Centaur::designing_employees.edit', ['designingEmployee' => $designingEmployee,'designings' => $designings, 'employees_designins' => $employees_designins]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $designingEmployee = DesigningEmployee::find($id);
        if($designingEmployee) {
            $designingEmployee->delete();

            return "Podatak je obrisan";
        } else {
            return "Nema podatka za brisanje";
        }
       
    }
}
