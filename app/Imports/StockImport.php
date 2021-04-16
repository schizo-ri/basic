<?php

namespace App\Imports;

use App\Models\Stock;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Sentinel;

class StockImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        ini_set('memory_limit','-1');
     
        return new Stock([
            'name'          => $row['name'], 
            'product_number'=> $row['product_number'], 
            'price'         => $row['price'], 
            'quantity'      => $row['quantity'], 
            'unit'          => $row['unit'], 
        ]);
    }
}