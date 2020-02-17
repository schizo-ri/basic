<?php

namespace App\Imports;

use App\Models\EquipmentList;
use App\Models\Preparation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EquipmentImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        ini_set('memory_limit','-1');
      
        $count = null;

        if(request()->preparation_id ) {
            $id = request()->preparation_id;           
        } else {
            $preparation = Preparation::orderBy('created_at', 'DESC')->first();
            $id = $preparation->id;
        }

        return new EquipmentList([
           'list_count'  => $count,
           'preparation_id'  => $id,
           'product_number' => $row['product_number'], 
           'mark' => $row['mark'], 
           'name'    => $row['name'], 
           'unit' => $row['unit'], 
           'quantity' => $row['quantity']          
        ]);
    }
}
