<?php

namespace App\Imports;

use App\Models\VehicalService;
use App\Models\Car;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Sentinel;

class VehicalServiceImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $employee = Sentinel::getUser()->employee;

        $reg = $row['car_id'];
        $car = Car::where('registration', $row['car_id'] )->first();
        
        if( $car ) {
            return new VehicalService([
                'car_id'        => $car->id,
                'employee_id'   => $employee->id, 
                'comment'        => $row['comment'],
                'km'            => $row['km'],
                'price'          => $row['price'],
                'date'          => date('Y-m-d', strtotime($row['date'])),
            ]);
        }
    }
}
