<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompetenceRating;

class CompetenceRatingController extends Controller
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
        $competenceRating = CompetenceRating::find($id);

        return view('Centaur::competence_ratings.edit', ['competenceRating' => $competenceRating]);
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
        $competenceRating = CompetenceRating::find($id);

        $data_rating = array(
            'rating'        => $request['rating'],
            'description'   => $request['description']
        );
        
        $competenceRating->updateCompetenceRating($data_rating);

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
        $competenceRating = CompetenceRating::find($id);
        $competenceRating->delete();
        
        
        session()->flash('success',  __('ctrl.data_delete'));
		return redirect()->back();
    }
}
