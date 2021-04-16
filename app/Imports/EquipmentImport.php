<?php

namespace App\Imports;

use App\Models\EquipmentList;
use App\Models\Preparation;
use App\Models\Stock;
use App\Models\DischargeStock;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Sentinel;

class EquipmentImport implements ToModel, WithHeadingRow
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
      
        $count = null;

        if(request()->preparation_id ) {
            $id = request()->preparation_id;           
        } else {
            $preparation = Preparation::orderBy('created_at', 'DESC')->first();
            $id = $preparation->id;
        }

        if($row['product_number'] != '') {
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
                'list_count'  => $count,
                'preparation_id'  => $id,
                'product_number' => $row['product_number'], 
                'mark' => $row['mark'], 
                'name'    => $row['name'], 
                'unit' => $row['unit'], 
                'quantity' => $row['quantity'],
                'user_id' => Sentinel::getUser()->id
                ]);
        }
    }
}
