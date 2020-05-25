<?php

namespace App\Imports;

use App\Models\EquipmentList;
use App\Models\Preparation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Sentinel;

class EquipmentImportSiemens implements ToModel, WithHeadingRow
{
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
            $item_level1 = EquipmentList::where('preparation_id', $id)->where('product_number', $stavka_level1)->first();
            $stavka_id_level1 = $item_level1->id;
            $stavka_id_level2 = null;
            $level1 = null;
        } else if( $stavka_level1 == '' &&  $stavka_level2 != '' ) {
            $item_level2 = EquipmentList::where('preparation_id', $id)->where('product_number', $row['stavka_id_level2'] )->first();
            $stavka_id_level2 = $item_level2->id;            
            $stavka_id_level1 = null;
            $level1 = null;
        } 

        return new EquipmentList([
            'list_count'  => null,
            'preparation_id'  => $id,
            'product_number' => $row['product_number'], 
            'mark' => $row['mark'], 
            'name'    => $row['name'], 
            'unit' => $row['unit'],
            'unit' => $row['unit'],
            'quantity' => $row['quantity'],
            'level1' =>  $level1,
            'stavka_id_level1' =>  $stavka_id_level1,
            'stavka_id_level2' =>  $stavka_id_level2,
            'user_id' => Sentinel::getUser()->id
        ]);  
    }
}
