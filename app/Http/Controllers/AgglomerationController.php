<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Agglomeration;
use App\Models\Contract;
use Cartalyst\Sentinel\Users\EloquentUser;

class AgglomerationController extends Controller
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
        $agglomerations = Agglomeration::get();

        $projektanti = EloquentUser::whereHas('roles', function ($query) {
            return $query->where('slug', 'projektant');
        })->orderBy('first_name','ASC')->with('designins')->get();

        $voditelji = EloquentUser::whereHas('roles', function ($query) {
            return $query->where('slug', 'voditelj');
        })->orderBy('first_name','ASC')->with('designins')->get();

        return view('Centaur::agglomerations.index', ['agglomerations' => $agglomerations,'projektanti' => $projektanti,'voditelji' => $voditelji]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $contract = Contract::find($request['contract_id']);

        $projektanti = EloquentUser::whereHas('roles', function ($query) {
            return $query->where('slug', 'projektant');
        })->orderBy('first_name','ASC')->with('designins')->get();

        $voditelji = EloquentUser::whereHas('roles', function ($query) {
            return $query->where('slug', 'voditelj');
        })->orderBy('first_name','ASC')->with('designins')->get();

        return view('Centaur::agglomerations.create',['contract_id' => $contract->id, 'projektanti' => $projektanti,'voditelji' => $voditelji]);
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
            'name'          => $request['name'],
            'contract_id'   => $request['contract_id'],
            'manager'       => $request['manager'],
            'designer'      => $request['designer'],
            'comment'       => $request['comment']
        );

        $agglomeration = new Agglomeration();
        $agglomeration->saveAgglomeration($data);

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
        $contract = Contract::find($id);
        $agglomerations = null;
        $group_list = null;
        $sum_contract = 0;
        $sum_agglomerations = 0;

        if($contract) {
            $group_list = $contract->hasList->pluck('group')->unique();
           
            if( count($contract->hasList) >0) {
                $sum_contract = $contract->hasList->sum(function ($row) {
                    return $row->quantity * $row->price;
                });
            }

            $agglomerations = $contract->hasAgglomeration;
            if( count($agglomerations) >0 ) {
                foreach( $agglomerations as $agglomeration) {
                    $stations = $agglomeration->hasStation;
                    if( count($stations) >0 ) {
                        foreach ($stations as $station) {
                            $sum_agglomerations += $station->hasList->sum(function ($row) {
                                return $row->quantity * $row->price;
                            });
                        } 
                    }
                }
            }
        }

        $projektanti = EloquentUser::whereHas('roles', function ($query) {
            return $query->where('slug', 'projektant');
        })->orderBy('first_name','ASC')->with('designins')->get();

        $voditelji = EloquentUser::whereHas('roles', function ($query) {
            return $query->where('slug', 'voditelj');
        })->orderBy('first_name','ASC')->with('designins')->get();

        return view('Centaur::agglomerations.show', ['contract' => $contract, 'agglomerations' => $agglomerations,'projektanti' => $projektanti,'voditelji' => $voditelji,'sum_contract' => $sum_contract,'sum_agglomerations' => $sum_agglomerations, 'group_list' => $group_list]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $agglomeration = Agglomeration::find($id);
        
        return view('Centaur::agglomerations.edit', ['agglomeration' => $agglomeration]);
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
        $agglomeration = Agglomeration::find($id);

        $data = array(
            'name'          => $request['name'],
            'contract_id'   => $request['contract_id'],
            'manager'       => $request['manager'],
            'designer'      => $request['designer'],
            'comment'       => $request['comment']
        );

        $agglomeration->updateAgglomeration($data);

        session()->flash('success', "Podaci su ispravljeni");
        
        return redirect()->back();
    }

    public function updateAgglomeration(Request $request)
    {
        $agglomeration = Agglomeration::find($request['id']);
      
        if(isset($request['name'])) {
            $data = array('name' => $request['name']);
        } else if ($request['manager'])  {
            $data = array('manager' => $request['manager']);
        } else if ($request['designer'])  {
            $data = ['designer' => $request['designer']];
        } else if ($request['comment'])  {
            $data = ['comment' => $request['comment']];
        }
  
        $agglomeration->updateAgglomeration($data);
        
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
        $agglomeration = Agglomeration::find($id);
       
        $stations = $agglomeration->hasStation;
        if( count(  $stations ) >0 ) {
            foreach( $stations as $station ) {
                $list = $station->hasList;
                if( count( $list ) >0 ) {
                    foreach( $list as $item ) {
                        $item->delete();
                    }
                }
                $station->delete();
            }
        }
        
        if($agglomeration) {
            $agglomeration->delete();
        }

        $message = session()->flash('success', "Aglomeracija je obrisana");                    
        return redirect()->back()->withFlashMessage($message);
    }
}
