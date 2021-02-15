<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AgglomerationStationList;
use App\Models\AgglomerationStation;
use App\Models\Agglomeration;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StationListImport;
use Log;

class AgglomerationStationListController extends Controller
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
    public function create(Request $request)
    {
        $agglomerationStation = AgglomerationStation::find($request['station_id']);
    
        return view('Centaur::agglomeration_station_lists.create', ['station_id' => $agglomerationStation->id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $agglomerationStation = AgglomerationStation::find($request['station_id']);

        if(request()->file('file')) {
            try {
                Excel::import(new StationListImport, request()->file('file'), request()->station_id);
            } catch (Exception $e) {
                Log::info('Caught exception: ',  $e->getMessage(), "\n");
                session()->flash('error', "Podaci nisu spremljeni, provjeri dokumenat...");
        
                return redirect()->back();
            }
            
            session()->flash('success', "Podaci su spremljeni");
        
            return redirect()->back();
        } else {
            session()->flash('error', "Nije odabran dokumenat");
        
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $agglomerationStation = AgglomerationStation::find($id);

        $agglomerationList = $agglomerationStation->hasList;
        $sum_station = $agglomerationList->sum(function ($row) {
            return $row->quantity * $row->price;
        });

        return view('Centaur::agglomeration_station_lists.show', ['agglomerationStation' => $agglomerationStation, 'agglomerationList' => $agglomerationList, 'sum_station' => $sum_station]);
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
        $agglomerationStationItem = AgglomerationStationList::find($id);

        $agglomerationStationItem->delete();
        
        $message = session()->flash('success', "Stavka je obrisana");                    
        return redirect()->back()->withFlashMessage($message);
    }

    
    public function updateList(Request $request)
    {
        $agglomerationStationItem = AgglomerationStationList::find($request['id']);

        if(isset($request['quantity'])) {
            $data = array('quantity' => $request['quantity']);
        } 
  
        $agglomerationStationItem->updateAgglomerationStationList($data);
        
        return "sve ok";
    }

}
