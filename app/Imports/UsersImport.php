<?php

namespace App\Imports;

use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
           'email'      => trim($row['email']),
           'password'   =>  Hash::make(trim($row['password'])), 
           'first_name' => trim($row['first_name']),
           'last_name'  => trim($row['last_name']),
        ]);
    }

}
