<?php

namespace App\Imports;

use App\Models\AgglomerationStationList;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StationListImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        ini_set('memory_limit','-1');
     
        if(request()->station_id ) {
            $station_id = request()->station_id;
            if($row['reference'] != '') {
                return new AgglomerationStationList([
                    'station_id'   => $station_id,
                    'reference'     => $row['reference'], 
                    'group'         => $row['group'], 
                    'description'   => $row['description'], 
                    'price'         => $row['price'], 
                    'quantity'      => $row['quantity']
                ]);
            }
        } else {
            return false;
        }
    }
}
