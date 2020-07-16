<?php

namespace App\Exports;

use App\WorkRecord;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class WorkRecordExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('Centaur::work_records.show', [
            'invoices' => Invoice::all()
        ]);
    }
}
