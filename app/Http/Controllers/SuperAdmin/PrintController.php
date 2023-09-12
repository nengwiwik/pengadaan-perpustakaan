<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Procurement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Procurement $procurement)
    {
        // return view('export.procurement', compact('procurement'));
        $pdf = Pdf::loadView('export.procurement', compact('procurement'));
        $title = str($procurement->code)->slug();
        return $pdf->setPaper('a4', 'landscape')->download('pengadaan-' . $title . '.pdf');
    }
}
