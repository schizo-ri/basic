<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EquipmentList;
use App\Models\ListReport;
use App\Models\ListUpdate;
use App\Models\Preparation;
use Illuminate\Support\Facades\Mail;
use App\Mail\EquipmentUpdateMail;
use DateTime;
use DateTimeZone;

class EquipmentListUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:listUpdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Equipment list';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $today = new DateTime('now', new DateTimeZone('Europe/Zagreb'));
        $hour = date_format($today, 'H');
        $yesterday = date_modify( new DateTime('now'), '-1day');
        $before_all = array();
        $after_all = array();
        $lists_after = array();
        if($hour == 11 || $hour == 11+1 || $hour == 11-1) {

            $lists_after = ListUpdate::join('equipment_lists','list_updates.item_id','equipment_lists.id')
            ->select('list_updates.*','equipment_lists.preparation_id','equipment_lists.quantity as quantity_orderd','equipment_lists.delivered as delivered_before')
            ->whereDay('list_updates.created_at',date_format($yesterday,'d'))
            ->whereMonth('list_updates.created_at',date_format($yesterday,'m'))
            ->whereYear('list_updates.created_at',date_format($yesterday,'Y'))
            ->whereTime('list_updates.created_at', '>=','15:00:00')
            ->whereTime('list_updates.created_at', '<=','23:59:59')
            ->orderBy('id')->get();

            $lists_after = $lists_after->merge($lists_after = ListUpdate::join('equipment_lists','list_updates.item_id','equipment_lists.id')
            ->select('list_updates.*','equipment_lists.preparation_id','equipment_lists.quantity as quantity_orderd','equipment_lists.delivered as delivered_before')
            ->whereDay('list_updates.created_at',date_format($today,'d'))
            ->whereMonth('list_updates.created_at',date_format($today,'m'))
            ->whereYear('list_updates.created_at',date_format($today,'Y'))
            ->whereTime('list_updates.created_at', '>=','00:00:00')
            ->whereTime('list_updates.created_at', '<=','11:00:00')
            ->orderBy('id')->get());

        }
        if($hour == 15 ||  $hour == 15+1 || $hour == 15-1) {

            $lists_after = ListUpdate::join('equipment_lists','list_updates.item_id','equipment_lists.id')
            ->select('list_updates.*','equipment_lists.preparation_id','equipment_lists.quantity as quantity_orderd','equipment_lists.delivered as delivered_before')
            ->whereDay('list_updates.created_at',date_format($today,'d'))
            ->whereMonth('list_updates.created_at',date_format($today,'m'))
            ->whereYear('list_updates.created_at',date_format($today,'Y'))
            ->whereTime('list_updates.created_at', '>=','11:00:00')
            ->whereTime('list_updates.created_at', '<=','15:00:00')
            ->orderBy('id')->get();

        }
        if(isset($lists_after)) {
            $preparation_ids = array();
    
            foreach( $lists_after as $list ) {
                $list_after_id =  $list->id;
            array_push($preparation_ids, $list->preparation_id );           
            }
        
            $preparation_ids = array_unique($preparation_ids);
            
            foreach ($preparation_ids as $preparation_id) {
               
                $preparation = Preparation::find($preparation_id);
                $preparation_lists_after = $lists_after->where('preparation_id', $preparation_id);    
        
                if( count($preparation_lists_after ) > 0) {
                    foreach ($preparation_lists_after as $preparation_item_after) {
                    
                        $report_before = ListReport::where('item_id', $preparation_item_after->item_id)->orderBy('created_at','DESC')->first(); //ako je već bilo izvještaja
                        $list_before = array();
                        $list_after = array();
                        $delivered_before = 0;
                        $delivered_after = 0;
                        $class = '';

                        $listUpdates_after = ListUpdate::where('item_id', $preparation_item_after->item_id )->orderBy('created_at','DESC')->get(); // svi update za stavku

                        if( count($listUpdates_after ) > 0) {
                            foreach ($listUpdates_after as $listUpdate) {
                                $delivered_after += $listUpdate->quantity;  //ukupno sada isporučeno
                            }
                        }
                        if($hour == 11 || $hour == 11-1) {
                            $date_before = date_format($yesterday,'Y-m-d') . ' 15:00:00';
                        } 
                        if($hour == 15 || $hour == 15-1) {
                            $date_before = date_format($today,'Y-m-d') . ' 11:00:00';
                        }
                        
                        $listUpdates_before = ListUpdate::where('item_id', $preparation_item_after->item_id )->whereDate('created_at','<', $date_before )->get(); // svi update za stavku

                        //listUpdates_before 

                        if($report_before) {   //ako je već bilo izvještaja
                            $delivered_before = $report_before->delivered_after;   // isporučeno kao prije isporučeno 0
                        } else {
                           /*  if( $preparation_item_after->quantity) {
                                $delivered_before += $preparation_item_after->quantity;
                            }  */
                            if( count($listUpdates_before ) > 0) {
                                foreach ($listUpdates_before as $listUpdate) {
                                    $delivered_before += $listUpdate->quantity;  //ukupno sada isporučeno
                                }
                            } 
                        }
        
                        if (! $delivered_after) {
                            $class = "not_delivered";
                        } else if( $preparation_item_after->quantity_orderd > $delivered_after ) {
                            $class = "partial";
                        } else if( $preparation_item_after->quantity_orderd <= $delivered_after) {   //4
                            $class = "all_delivered";
                        }
        
                        $list_after += ['product_number' => $preparation_item_after->equipmentList->product_number];
                        $list_after += ['id' => $preparation_item_after->item_id];
                        $list_after += ['name'     => $preparation_item_after->equipmentList->name ];
                        $list_after += ['mark'     => $preparation_item_after->equipmentList->mark ];
                        $list_after += ['quantity' => $preparation_item_after->quantity_orderd];
                        $list_after += ['delivered' => $delivered_after];
                        $list_after += ['class'    => $class];

                        array_push($after_all, $list_after );
                        

                        // prije lista
                        $class = '';

                        if (! $delivered_before) {
                            $class = "not_delivered";
                        } else if( $preparation_item_after->quantity_orderd > $delivered_before ) {
                            $class = "partial";
                        } else if( $preparation_item_after->quantity_orderd <= $delivered_before) {   //4
                            $class = "all_delivered";
                        }


                        $list_before += ['product_number' => $preparation_item_after->equipmentList->product_number];
                        $list_before += ['id' => $preparation_item_after->item_id];
                        $list_before += ['name'     => $preparation_item_after->equipmentList->name ];
                        $list_before += ['mark'     => $preparation_item_after->equipmentList->mark ];
                        $list_before += ['quantity' => $preparation_item_after->quantity_orderd];
                        $list_before += ['delivered' => $delivered_before];
                        $list_before += ['class'    => $class];

                        array_push($before_all, $list_before );

                        $data = array(
                            'item_id'  => $preparation_item_after->item_id,
                            'delivered_before'  => $delivered_before,
                            'delivered_after'  => $delivered_after,
                        );                    
                        
                        $listReport = new ListReport();
                        $listReport->saveListReport($data);  

                    }
                }

                $mails = array($preparation->manager->email, $preparation->designed->email,'jelena.juras@duplico.hr' );
                foreach (array_unique($mails) as $email) {
                    Mail::to($email)->send(new EquipmentUpdateMail($preparation,$before_all, $after_all));
                }
                $before_all = array();
                $after_all = array();
            }
        }
    }
}