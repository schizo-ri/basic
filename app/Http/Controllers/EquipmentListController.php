<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EquipmentList;
use App\Imports\EquipmentImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use App\Models\Emailing;
use App\Models\Preparation;
use App\Mail\EquipmentMail;
use Carbon;


class EquipmentListController extends Controller
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
        $equipments = EquipmentList::where('preparation_id', $id)->get();
        $equipments_dates = $equipments->unique('created_at'); 
        $list_dates = array();
        foreach ($equipments_dates as $date ) {
           array_push($list_dates, $date->created_at->toDateTimeString());
        }       
   
        return view('Centaur::equipment_lists.edit', ['equipments' => $equipments, 'preparation_id' => $id, 'list_dates' => $list_dates]);
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
        foreach ($request['delivered'] as $key_delivered => $delivered) {
            if ($delivered != null) {
               foreach ($request['id'] as $key_id => $preparation_id) {
                   if ($key_id == $key_delivered) {
                       $equipment_list = EquipmentList::find($preparation_id);
                       $data = array(
                            'delivered'  => $delivered,
                        );
                  
                        $equipment_list->updateEquipmentList($data);
                   }
               }
            }           
        }

        $preparation = Preparation::find($id);
        $send_to_mail = array();

        array_push( $send_to_mail, $preparation->manager->email);
        array_push( $send_to_mail, $preparation->designed->email);

        foreach( array_unique($send_to_mail) as $email) {
            Mail::to($email)->send(new EquipmentMail($preparation)); 
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

    public function import ()
    {
        try {
            Excel::import(new EquipmentImport, request()->file('file'));

        } catch (\Throwable $th) {
            session()->flash('error', "Došlo je do problema, dokument nije učitan!");
        
            return redirect()->back();
        }
       
        session()->flash('success', "Dokument je učitan");
        return back();
    }
}
