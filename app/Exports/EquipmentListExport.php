<?php

namespace App\Exports;

use App\Models\EquipmentList;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EquipmentListExport implements FromArray, WithHeadings, ShouldAutoSize
{
    protected $invoices;

    public function __construct(array $equipments)
    {
        $this->equipments = $equipments;
    }

    public function headings(): array
    {
        return [
            'product_id/default_code',
            'product_id/name_template',
            'uom_id/name',
            'product_qty',
            'delivered',
        ];
    }

    public function array(): array
    {
        return $this->equipments;
    }
}
