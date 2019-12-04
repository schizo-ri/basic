<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Preparation;
use App\Models\Employee;

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
       
        return view('Centaur::preparations.index', ['preparations' => $preparations]);
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
            'preparation'  => $request['preparation'],
            'mechanical_processing'  => $request['mechanical_processing'],
        );
      
        $preparation = new Preparation();
        $preparation->savePreparation($data);
        
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
            'preparation'  => $request['preparation'],
            'mechanical_processing'  => $request['mechanical_processing'],
        );
      
        $preparation->updatePreparation($data);
        
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

        session()->flash('success', "Podaci su obrisani");
        
        return redirect()->back();
    }
}
