<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EquipmentList;
use App\Models\ListUpdate;
use App\Imports\EquipmentImport;
use App\Imports\EquipmentImportMultiple;
use App\Imports\EquipmentImportSiemens;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Mail;
use App\Models\Emailing;
use App\Models\Preparation;
use App\Mail\EquipmentMail;
use App\Exports\MarksExport;
use App\Exports\EquipmentListExport;
use App\Mail\ErrorMail;
use Carbon;
use Sentinel;

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
    public function store(Request $request)
    {
        $before_all = array();       
        $after_all = array();
        
        foreach ($request['delivered'] as $key_delivered => $delivered) {
            if ( $delivered != null ) {
               foreach ($request['id'] as $key_id => $list_id) {
                   if ($key_id == $key_delivered) {
                        $list_before = array();
                        $list_after = array();
                        $promjena = false;

                        $equipment_list = EquipmentList::find($list_id);

                        if( $equipment_list->delivered) {
                            $delivered_quantity = $equipment_list->delivered;   //5
                        } else {
                            $delivered_quantity = 0;
                        }

                        $listUpdates = ListUpdate::where('item_id', $list_id )->get(); //1

                        if( count($listUpdates ) > 0) {
                            foreach ($listUpdates as $listUpdate) {
                                $delivered_quantity += $listUpdate->quantity;
                            }
                        }
                       
                        if( $delivered_quantity != $delivered ) {
                            $class = '';

                            if (! $equipment_list->delivered) {
                                $class = "not_delivered";
                            } else if( $equipment_list->quantity > $delivered_quantity ) {
                                $class = "partial";
                            } else if( $equipment_list->quantity <= $delivered_quantity) {
                                $class = "all_delivered";
                            }
    
                            $list_before += ['product_number' => $equipment_list->product_number];
                            $list_before += ['id' => $equipment_list->id];
                            $list_before += ['name'     => $equipment_list->name ];
                            $list_before += ['mark'     => $equipment_list->mark ];
                            $list_before += ['quantity' => $equipment_list->quantity];
                            $list_before += ['delivered' => $delivered_quantity];
                            $list_before += ['class'    => $class];
                           
                            $upis_kolicine = 0;
                            $upis_kolicine = $delivered - $delivered_quantity;
                           

                            $data = array(
                                'quantity'  => $upis_kolicine,
                                'item_id'  => $list_id,
                                'user_id'  => Sentinel::getUser()->id,
                            );
                            $promjena = true;

                            $listUpdate = new ListUpdate();
                            $listUpdate->saveListUpdate($data);  
                            $class = '';

                            $listUpdates = ListUpdate::where('item_id', $equipment_list->id )->get();  //nove liste
                            $delivered_quantity = $equipment_list->delivered; 
                            foreach ($listUpdates as $key => $listUpdate) {
                                $delivered_quantity += $listUpdate->quantity;
                            }
                            
                            if (! $delivered) {
                                $class = "not_delivered";
                            } else if( $equipment_list->quantity > $delivered_quantity ) {
                                $class = "partial";
                            } else if( $equipment_list->quantity <= $delivered_quantity) {
                                $class = "all_delivered";
                            }
    
                            $list_after += ['product_number' => $equipment_list->product_number];
                            $list_after += ['name' => $equipment_list->name];
                            $list_after += ['mark'     => $equipment_list->mark ];
                            $list_after += ['quantity' => $equipment_list->quantity];
                            $list_after += ['delivered' =>  $delivered_quantity];
                            $list_after += ['class' => $class];
    
                            if($promjena == true) {
                                array_push($before_all, $list_before );
                                array_push($after_all, $list_after );
                            }                
                        }
                   }
               }
            }           
        }

        $preparation_id = Preparation::where('id',$equipment_list->preparation_id)->first()->id;

        $preparation = Preparation::find($preparation_id);
        $send_to_mail = array();
        if($preparation->manager) {
            array_push( $send_to_mail, $preparation->manager->email);
        }
        if( $preparation->designed ) {
            array_push( $send_to_mail, $preparation->designed->email);
        }
       // array_push( $send_to_mail, 'jelena.juras@duplico.hr');
        
     
        foreach( array_unique($send_to_mail) as $email) {
         /*    Mail::to($email)->send(new EquipmentMail($preparation, $before_all, $after_all )); */
        }
       
        session()->flash('success', "Podaci su upisani");
        
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addItem(Request $request)
    {
        $preparation_id = $request['preparation_id'];
        $product_number = $request['product_number'];      
        $name = $request['name'];
        $mark = $request['mark'];
        $unit = $request['unit'];
        $quantity = $request['quantity'];       

        $data = array(
            "preparation_id"    => intval($preparation_id),
            "product_number"    => $product_number,            
            "name"              => $name,
            "unit"              => $unit,
            "quantity"          => $quantity,            
            'user_id'           => Sentinel::getUser()->id
        );

        if(isset($request['replaced_item_id'])) {
            $data += ['replaced_item_id'=> $request['replaced_item_id']];
        }
        if(isset($request['stavka_id_level1']) && $request['stavka_id_level1'] != '') {
            $item_level1 = EquipmentList::where('preparation_id', $preparation_id)->where('product_number', $request['stavka_id_level1'])->first();
            if( $item_level1 ) {
                $data += ["stavka_id_level1"=> $item_level1->id];
            }
        }
        if(isset($request['stavka_id_level2']) && $request['stavka_id_level2'] != '') {
            $item_level2 = EquipmentList::where('preparation_id', $preparation_id)->where('product_number', $request['stavka_id_level2'])->first();
            if( $item_level2 ) {
                $data += ["stavka_id_level2"=> $item_level2->id ];
            }
        }
        if(isset($request['mark']) && $request['mark'] != '' ) {
            $data += ["mark"=> $request['mark']];
        } else if(isset($request['mark']) && $request['mark'] == '' ) {
            $data += ["mark"=> null];
        }

        $equipment_list = new EquipmentList();
        $equipment_list->saveEquipmentList($data);
        
       exit;
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
        $equipment_level1 = EquipmentList::find($id);
        $preparation_id = $equipment_level1->preparation_id;
        $equipments = EquipmentList::where('preparation_id',$preparation_id )->get();

        $listUpdates = collect();
        foreach ($equipments as $equipment ) {
            $listUpdates =  $listUpdates->merge(ListUpdate::where('item_id',$equipment->id)->orderBy('created_at', 'ASC')->get());
        }
     //   $listUpdates = ListUpdate::orderBy('created_at', 'ASC')->get();  
      
        
        $equipments_dates = $equipments->unique('created_at'); 
        $list_dates = array();
        foreach ($equipments_dates as $date ) {
           array_push($list_dates, $date->created_at->toDateTimeString());
        }       
     
        return view('Centaur::equipment_lists.edit', ['equipments' => $equipments,'equipment_level1' => $equipment_level1, 'preparation_id' => $preparation_id, 'list_dates' => $list_dates, 'listUpdates' => $listUpdates]);
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
     
        $before_all = array();
       
        $after_all = array();
        
        foreach ($request['delivered'] as $key_delivered => $delivered) {
            if ($delivered != null) {
               foreach ($request['id'] as $key_id => $preparation_id) {
                   if ($key_id == $key_delivered) {
                    $list_before = array();
                    $list_after = array();
                    $promjena = false;
                    $equipment_list = EquipmentList::find($preparation_id);
 
                    $class = '';

                    if (!$equipment_list->delivered) {
                        $class = "not_delivered";
                    } else if($equipment_list->quantity > $equipment_list->delivered ) {
                        $class = "partial";
                    } else if($equipment_list->quantity == $equipment_list->delivered) {
                        $class = "all_delivered";
                    }

                    $list_before += ['product_number' => $equipment_list->product_number];
                    $list_before += ['name'     => $equipment_list->name ];
                    $list_before += ['mark'     => $equipment_list->mark ];
                    $list_before += ['quantity' => $equipment_list->quantity];
                    $list_before += ['delivered' => $equipment_list->delivered];
                    $list_before += ['class'    => $class];
                    
                    $data = array(
                        'delivered'  => $delivered,
                    );
                    if($equipment_list->delivered != $delivered) {
                        $promjena = true;
                    }
                    $equipment_list->updateEquipmentList($data);

                    $class = '';

                    if (!$delivered) {
                        $class = "not_delivered";
                    } else if($equipment_list->quantity > $delivered ) {
                        $class = "partial";
                    } else if($equipment_list->quantity == $delivered) {
                        $class = "all_delivered";
                    } 

                    $list_after += ['product_number' => $equipment_list->product_number];
                    $list_after += ['name' => $equipment_list->name];
                    $list_after += ['mark'     => $equipment_list->mark ];
                    $list_after += ['quantity' => $equipment_list->quantity];
                    $list_after += ['delivered' => $delivered];
                    $list_after += ['class' => $class];

                    if($promjena == true) {
                        array_push($before_all, $list_before );
                        array_push($after_all, $list_after );
                    }                    
                   }
               }
            }           
        }
       
        $preparation = Preparation::find($id);
        $send_to_mail = array();
        if($preparation->manager) {
            array_push( $send_to_mail, $preparation->manager->email);
        }
        if($preparation->designed) {
            array_push( $send_to_mail, $preparation->designed->email);
        }
       // array_push( $send_to_mail, 'jelena.juras@duplico.hr');
        
        foreach( array_unique($send_to_mail) as $email) {
          /*   Mail::to($email)->send(new EquipmentMail($preparation, $before_all, $after_all )); */
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
        $item_level1 = EquipmentList::find($id);
        
        $items_level2 = EquipmentList::where('stavka_id_level1', $item_level1->id)->get();
        if(count($items_level2)>0) {
            foreach ($items_level2 as $item_level2) {
                $items_level3 = EquipmentList::where('stavka_id_level2', $item_level2->id)->get();
                if(count($items_level3)>0) {
                    foreach ($items_level3 as $item_level3) {
                        $item_level3->delete();
                    }
                }  
                $item_level2->delete();              
            }
        }
        $item_level1->delete();
        
        session()->flash('success', "Ormar je obrisan");
        
        return redirect()->back();
    }

    public function import ()
    {
        try {
            Excel::import(new EquipmentImport, request()->file('file'));
        } catch (\Throwable $th) {
          
            $email = 'jelena.juras@duplico.hr';
            $url = $_SERVER['REQUEST_URI'];
            Mail::to($email)->send(new ErrorMail($th->getMessage(), $url)); 
            
            session()->flash('error', "Došlo je do problema, dokument nije učitan!");
        
            return redirect()->back();
        } 
       
        session()->flash('success', "Dokument je učitan");
        return back();
    }

    public function import_with_replace ()
    {      
         try {
            Excel::import(new EquipmentImportMultiple, request()->file('file'));

        } catch (\Throwable $th) {
            
            $email = 'jelena.juras@duplico.hr';
            $url = $_SERVER['REQUEST_URI'];
            Mail::to($email)->send(new ErrorMail($th->getMessage(), $url)); 
            
            session()->flash('error', "Došlo je do problema, dokument nije učitan!");
        
            return redirect()->back();
        }  
       
        session()->flash('success', "Dokument je učitan");
        return back();
    }

    public function importSiemens ()
    {       
        try {
            Excel::import(new EquipmentImportSiemens, request()->file('file'));

        } catch (\Throwable $th) {
            
            $email = 'jelena.juras@duplico.hr';
            $url = $_SERVER['REQUEST_URI'];
            Mail::to($email)->send(new ErrorMail($th->getMessage(), $url)); 
            
            session()->flash('error', "Došlo je do problema, dokument nije učitan!");
        
            return redirect()->back();
        } 
       
        session()->flash('success', "Dokument je učitan");
        return back();
    }

    public function export($id) 
    {
        $lists = EquipmentList::where('preparation_id', $id)->get();
     
        $list_arr = array();
        foreach ($lists as $list) {
           $list_array = explode( ';', $list->mark);

           foreach ($list_array as $list_item) {
                array_push( $list_arr, [$list_item]);
           }
           
        }
      
        $export = new MarksExport($list_arr);
        
        return Excel::download($export, 'marks.xlsx');
    }

    public function exportList(Request $request) 
    {
        $equipments =  EquipmentList::where('preparation_id', $request['id'])->select('id','product_number','name','unit','quantity','delivered')->get();
       
        $listUpdates = ListUpdate::orderBy('created_at', 'ASC')->get();
       
        if( $request['status'] == 'no') {
            $equipments = $equipments->where('delivered', null);
        }

        $equipments2 = collect();
        if( $request['status'] == 'ok') {
            foreach ($equipments as $equipment) { 
              
                $listUpdates_item = $listUpdates->where('item_id', $equipment->id );
                $delivered = $equipment->delivered;
                
                foreach ($listUpdates_item as $listUpdate) {
                    $delivered +=  $listUpdate->quantity;                   
                }
                $equipment->delivered =  $delivered;
                if ( $equipment->quantity == $delivered ) {
                 
                    $equipments2->push($equipment);
                }
            }
        }
        if( $request['status'] == 'part') {
                foreach ($equipments as $equipment) {
                    $listUpdates_item = $listUpdates->where('item_id', $equipment->id );
                    $delivered = $equipment->delivered;
    
                    foreach ($listUpdates_item as $listUpdate) {
                        $delivered +=  $listUpdate->quantity;
                    }
                    $equipment->delivered =  $delivered;
                    if ( $delivered != null && $delivered < $equipment->quantity ) {
                        $equipments2->push($equipment);
                }
            }            
        }
        if( $equipments2->isNotEmpty() ) {
            $equipments = $equipments2;
        }
     
        $export = new EquipmentListExport([
            $equipments
        ]);
    
        return Excel::download($export, 'list.xlsx');
        
      //  return Excel::download($export, 'list.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function replaceItem (Request $request) 
    { 
        if($request['id']) {
            $equipment_item = EquipmentList::where('id', $request['id'])->first();

            $data = array(
                'replace_item'      => 1,
                'user_id'           => Sentinel::getUser()->id
            );

            $equipment_item->updateEquipmentList($data);
        }

        return redirect()->back();
    }

    public function multiReplaceItem ($id) 
    {
        $equipments = EquipmentList::where('preparation_id', $id)->get();
        $listUpdates = ListUpdate::orderBy('created_at', 'ASC')->get();  

        $equipments_dates = $equipments->unique('created_at'); 
        $list_dates = array();
        if($equipments_dates) {
            foreach ($equipments_dates as $date ) {
                array_push($list_dates, $date->created_at->toDateTimeString());
            }
        }
   
        return view('Centaur::equipment_lists.multiReplaceItem', ['equipments' => $equipments, 'preparation_id' =>$id, 'list_dates' => $list_dates, 'listUpdates' => $listUpdates]);
    }

    public function multiReplaceStore (Request $request) {
       
         foreach ($request['product_number'] as $key_product_number => $product_number) {
            $mark = $request['mark'][$key_product_number];
            $name = $request['name'][$key_product_number];
            $unit = $request['unit'][$key_product_number];
            $quantity = $request['quantity'][$key_product_number];
            $replaced_item_id = $request['id'][$key_product_number];

            $data = array(
                "preparation_id"    => intval($request['preparation_id']),
                "product_number"    => $product_number,
                "mark"              => $mark,
                "name"              => $name,
                "unit"              => $unit,
                "quantity"          => $quantity,
                "replaced_item_id"  => $replaced_item_id,
                'user_id'           => Sentinel::getUser()->id
            );
            
            $equipment_list = new EquipmentList();
            $equipment_list->saveEquipmentList($data);

            $equipment_item = EquipmentList::where('id', $replaced_item_id)->first();

            $data = array(
                'replace_item'      => 1,
                'user_id'           => Sentinel::getUser()->id
            );

            $equipment_item->updateEquipmentList($data);
        }
        session()->flash('success', "Podaci su upisani");
        
        return redirect()->back(); 
        
       
    }

    public function equipmentList($id) 
    {
        $equipmentLists = EquipmentList::where('preparation_id', $id)->get(); 

        $equipmentLists_withmark = $equipmentLists->where('mark', '<>',null)->first();
        if($equipmentLists_withmark) {
            $hasmark = true;
        } else {
            $hasmark = false;
        }

        if( $equipmentLists->where('level1',1 )->first()) {
            $equipmentLists =  $equipmentLists->where('level1',1 );
        }
        $delivered = PreparationController::delivered( $id);
        return ['equipmentLists' => json_encode($equipmentLists->toArray()), 'delivered' => $delivered, 'hasmark' => $hasmark ];
    }
}
   