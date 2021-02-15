<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AgglomerationStation;
use App\Models\Agglomeration;

class AgglomerationStationController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Request $request )
    {
        $agglomeration = Agglomeration::find($request['agglomeration_id']);

        return view('Centaur::agglomeration_stations.create', ['agglomeration_id' => $agglomeration->id]);
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
            'agglomeration_id'  => $request['agglomeration_id'],
            'name'              => $request['name'],
            'comment'           => $request['comment']
        );

        $agglomerationStation = new AgglomerationStation();
        $agglomerationStation->saveAgglomerationStation($data);

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
        $agglomeration = Agglomeration::find($id);
        $sum_agglomerations = 0;
        $agglomerationStations = null;
        if($agglomeration) {
            $agglomerationStations = $agglomeration->hasStation;
        }

        if( $agglomerationStations && count($agglomerationStations) >0 ) {
            foreach ($agglomerationStations as $station) {
                $sum_agglomerations += $station->hasList->sum(function ($row) {
                    return $row->quantity * $row->price;
                });
            } 
        }

        return view('Centaur::agglomeration_stations.show', ['agglomeration' => $agglomeration, 'agglomerationStations' => $agglomerationStations, 'sum_agglomerations' => $sum_agglomerations]);
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
        //
    }

    public function updateStation(Request $request)
    {
        $agglomerationStation = AgglomerationStation::find($request['id']);
      
        if(isset($request['name'])) {
            $data = array('name' => $request['name']);
        } else if ($request['comment'])  {
            $data = ['comment' => $request['comment']];
        }
  
        $agglomerationStation->updateAgglomerationStation($data);
        
        return "sve ok";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $agglomerationStation = AgglomerationStation::find($id);

        $list = $agglomerationStation->hasList;
        if( count( $list ) > 0 ) {
            foreach( $list as $item ) {
                $item->delete();
            }
        }

        $agglomerationStation->delete();
        
        $message = session()->flash('success', "Stanica je obrisana");                    
        return redirect()->back()->withFlashMessage($message);
    }
}
