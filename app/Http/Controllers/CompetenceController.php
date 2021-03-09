<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Competence;

class CompetenceController extends Controller
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
        $competences = Competence::get();

        return view('Centaur::competences.index', ['competences' => $competences]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Centaur::competences.create');
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
			'name'         => $request['name'],
			'type'         => $request['type']
		);
		
		$competence = new Competence();
        $competence->saveCompetence($data);
       
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
        $competence = Competence::find($id);

        return view('Centaur::competences.edit', ['competence' => $competence]);
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
        $competence = Competence::find($id);

        $data = array(
			'name'         => $request['name'],
			'type'         => $request['type']
		);
		
        $competence->updateCompetence($data);
       
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
        $competence = Competence::find($id);
        $competence->delete();

        session()->flash('success',  __('ctrl.data_delete'));
		return redirect()->back();
    }
}
