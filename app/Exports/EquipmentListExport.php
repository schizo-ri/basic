<?php

namespace App\Exports;

use App\Models\EquipmentList;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class EquipmentListExport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements FromArray, WithCustomValueBinder, WithHeadings, WithColumnFormatting, ShouldAutoSize
{
    protected $equipments;

    public function __construct(array $equipments)
    {
        $this->equipments = $equipments;
    }

    public function headings(): array
    {
        return [
            'id',
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

    public function columnFormats(): array
    {
        return [
            'B' => DataType::TYPE_STRING,
        ];
    }
}