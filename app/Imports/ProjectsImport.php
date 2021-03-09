<?php

namespace App\Imports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProjectsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        ini_set('memory_limit','-1');
        
        if($row['erp_id'] != '' && $row['name'] != '') {
            return new Project([
                'erp_id'        => $row['erp_id'],
                'customer_oib'  => $row['customer_oib'],
                'name'          => $row['name'],
                'employee_id'   => $row['employee_id'],
                'active'        => 1,
                'created_at'    => date('Y-m-d h:i:s'),
                'updated_at'    => date('Y-m-d h:i:s'),
            ]);
        }
      
    }
}
