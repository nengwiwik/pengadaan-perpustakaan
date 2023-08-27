<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Procurement;
use App\Models\ProcurementBook;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;

class PengadaanAktifDetailController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Procurement $procurement)
    {
        Paginator::useBootstrapFour();
        $data['type_menu'] = 'pengadaan';
        $data['title'] = "Data Buku | ID Pengadaan " . $procurement->code;
        $data['books'] = $procurement->books()->paginate();
        $data['procurement'] = $procurement;
        return view('superadmin.aktif', $data);
    }
}
