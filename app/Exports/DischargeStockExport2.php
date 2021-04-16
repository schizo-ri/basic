<?php

namespace App\Exports;

use App\Models\DischargeStock;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class DischargeStockExport2 extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements FromArray, WithCustomValueBinder, WithHeadings, ShouldAutoSize
{
    protected $dischargeStock;

    public function __construct(array $dischargeStock)
    {
        $this->dischargeStock = $dischargeStock;
    }

    public function headings(): array
    {
        return [
            'product_id/default_code',
            'product_id/name_template',
            'uom_id/name',
            'product_qty'
        ];
    }

    public function array(): array
    {
        return $this->dischargeStock;
    }
}