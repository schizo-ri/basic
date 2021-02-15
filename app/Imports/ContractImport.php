<?php

namespace App\Imports;

use App\Models\Contract;
use App\Models\ContractList;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ContractImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        ini_set('memory_limit','-1');

        $contract = Contract::orderBy('created_at', 'DESC')->first();
        $contract_id = $contract->id;
        
        return new ContractList([
            'contract_id'   => $contract_id, 
            'reference'     => $row['reference'], 
            'group'         => $row['group'], 
            'description'   => $row['description'], 
            'price'         => $row['price'], 
            'quantity'      => $row['quantity']
        ]);
    }
}
