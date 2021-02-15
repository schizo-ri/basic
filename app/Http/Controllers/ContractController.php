<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Contract;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ContractImport;

class ContractController extends Controller
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
        $contracts = Contract::get();

        return view('Centaur::contracts.index', ['contracts' => $contracts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Centaur::contracts.create');
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
            'number' => trim($request['number']),
            'supplier'  => $request['supplier'],
            'comment'  => $request['comment']
        );
       
        $contract = new Contract();
        $contract->saveContract($data);
        
        if(request()->file('file')) {
            Excel::import(new ContractImport, request()->file('file'));
        }

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

        return view('Centaur::contracts.show', ['contract' => $contract]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $contract = Contract::find($id);

        return view('Centaur::contracts.edit', ['contract' => $contract]);
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
        $contract = Contract::find($id);

        $data = array(
            'number' => trim($request['number']),
            'supplier'  => $request['supplier'],
            'comment'  => $request['comment']
        );
  
        $contract->updateContract($data);
        
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
        $contract = Contract::find($id);

        $list = $contract->hasList;
        if( count(  $list ) >0 ) {
            foreach( $list as $item ) {
                $item->delete();
            }
        }
        if($contract) {
            $contract->delete();
        }

        $message = session()->flash('success', "Ugovor je obrisan");                    
        return redirect()->route('contracts.index')->withFlashMessage($message);
    }
}
