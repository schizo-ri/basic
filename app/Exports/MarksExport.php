<?php

namespace App\Exports;

use App\Models\EquipmentList;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MarksExport implements FromArray, WithHeadings
{
  
    public function headings(): array
    {
        return [
            'oznaka',
        ];
    }

    public function __construct(array $marks)
    {
        $this->marks = $marks;
    }

    public function array(): array
    {
        return $this->marks;
    }
}
