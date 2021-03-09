<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConstructionSite;
use App\Http\Controllers\Controller;

class ConstructionSiteController extends Controller
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
        $construction_sites = ConstructionSite::get();

        return view('Centaur::construction_sites.index', ['construction_sites' => $construction_sites]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Centaur::construction_sites.create');
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
			'name'         => $request['name']
		);
		
		$constructionSite = new ConstructionSite();
        $constructionSite->saveConstructionSite($data);
       
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
        $construction_site = ConstructionSite::find($id);

        return view('Centaur::construction_sites.edit', ['construction_site' => $construction_site]);
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
        $construction_site = ConstructionSite::find($id);

        $data = array(
			'name'         => $request['name'],
			'type'         => $request['type']
		);
		
        $construction_site->updateConstructionSite($data);
       
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
        $construction_site = ConstructionSite::find($id);
        $construction_site->delete();

        session()->flash('success',  __('ctrl.data_delete'));
		return redirect()->back();
    }
}
