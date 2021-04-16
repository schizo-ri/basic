<?php

namespace App\Imports;

use App\Models\EquipmentList;
use App\Models\Preparation;
use App\Models\Stock;
use App\Models\DischargeStock;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Sentinel;

class EquipmentImportSiemens implements ToModel, WithHeadingRow
{
    private $stock;

    public function __construct()
    {
        $this->stock = Stock::get();
    }
    
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        ini_set('memory_limit','-1');

        if(request()->preparation_id ) {
            $id = request()->preparation_id;           
        } else {
            $preparation = Preparation::orderBy('created_at', 'DESC')->first();
            $id = $preparation->id;
        }

        $stavka_level1 = $row['stavka_id_level1'];
        $stavka_level2 = $row['stavka_id_level2'];

        if ( $stavka_level1 == '' &&  $stavka_level2 == '' ) {
            $stavka_id_level1 = null;
            $stavka_id_level2 = null;
            $level1 = 1;
        } elseif( $stavka_level1 != '' &&  $stavka_level2 == '' ) {
            $item_level1 = EquipmentList::where('preparation_id', $id)->where('product_number', $stavka_level1)->orderBy('created_at','DESC')->first();
            $stavka_id_level1 = $item_level1->id;
            $stavka_id_level2 = null;
            $level1 = null;
        } else if( $stavka_level1 == '' &&  $stavka_level2 != '' ) {
            $item_level2 = EquipmentList::where('preparation_id', $id)->where('product_number', $row['stavka_id_level2'] )->orderBy('created_at','DESC')->first();
            $stavka_id_level2 = $item_level2->id;            
            $stavka_id_level1 = null;
            $level1 = null;
        } 
        if( $row['product_number'] != null ) {
            if( Sentinel::inRole('skladiste_upload')) {
                $item = $this->stock->where('product_number',  $row['product_number'])->first();
                if ( $item ) {
                    $quantity = $item->quantity;
                    $discharges = DischargeStock::where('item_id', $item->id )->get()->sum('quantity');
                    $total_quantity =  $quantity - $discharges;
                    if ( $total_quantity > 0) {
                        $q =  $total_quantity >= $row['quantity'] ? $row['quantity'] : $total_quantity;
                        $data = array(
                            'item_id' => $this->stock->where('product_number',  $row['product_number'])->first()->id, 
                            'quantity' => $q,
                            'preparation_id' => $id

                        );
                        $dischargeStock = new DischargeStock();
                        $dischargeStock->saveDischargeStock($data);
                    }
                }
            }

            return new EquipmentList([
                'list_count'  => null,
                'preparation_id'  => $id,
                'product_number' => $row['product_number'], 
                'mark' => $row['mark'], 
                'name'    => $row['name'], 
                'unit' => $row['unit'],
                'quantity' => $row['quantity'],
                'level1' =>  $level1,
                'stavka_id_level1' =>  $stavka_id_level1,
                'stavka_id_level2' =>  $stavka_id_level2,
                'user_id' => Sentinel::getUser()->id
            ]);  
        }
      
    }
}