<?php

namespace App\Imports;

use App\Models\Fuel;
use App\Models\Car;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Sentinel;

class FuelImport implements ToModel, WithHeadingRow
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
            return new Fuel([
                'car_id'        => $car->id,
                'employee_id'   => $employee->id, 
                'liters'        => $row['liters'],
                'km'            => $row['km'],
                'date'          => date('Y-m-d', strtotime($row['date'])),
            ]);
        }
        
    }
}
