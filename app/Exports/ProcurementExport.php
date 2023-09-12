<?php

namespace App\Exports;

use App\Models\Procurement;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ProcurementExport implements FromView
{
    public function __construct(public Procurement $procurement) {
    }

    public function view(): View
    {
        return view('export.procurement', [
            'invoices' => Procurement::all()
        ]);
    }
}
