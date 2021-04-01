<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\CompetenceQuestion;

class CompetenceQuestionImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function model(array $row)
    {
        return new CompetenceQuestion([
            'group_id'      => $row['qroup_id'],
            'name'          => $row['name'],
            'description'   => $row['description'],
            'rating'        => $row['rating'],
        ]);
    }
}
