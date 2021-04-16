<?php

namespace App\Exports;

use App\Models\Okr;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class OkrExport implements FromArray, WithHeadings, ShouldAutoSize, WithColumnFormatting
{
    public function headings(): array
    {
        return [
            'Tip',
            'Naziv',
            'Opis',
            'Kvartal',
            'Djelatnik',
            'Status',
        ];
    }

      /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'B' => Alignment::WRAP,
            'C' => Alignment::WRAP,
        ];
    }


    public function __construct(array $okrs)
    {
        $this->okrs = $okrs;
    }

    public function array(): array
    {
        return $this->okrs;
    }
}
