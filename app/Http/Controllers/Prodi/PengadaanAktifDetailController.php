<?php

namespace App\Http\Controllers\Prodi;

use App\Http\Controllers\Controller;
use App\Models\ProcurementBook;
use App\Models\Procurement;
use App\Traits\CalculateBooks;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class PengadaanAktifDetailController extends Controller
{
    use CalculateBooks;

    public function __invoke(Request $request, Procurement $procurement)
    {
        Paginator::useBootstrapFour();
        $data['title'] = "Data Buku | ID Pengadaan " . $procurement->code;
        $data['books'] = ProcurementBook::where([
            'procurement_id' => $procurement->getKey(),
            'major_id' => Auth::user()->major_id,
        ])->paginate(4);
        return view('prodi.aktif', $data);
    }
}
