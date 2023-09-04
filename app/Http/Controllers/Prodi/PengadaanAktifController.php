<?php

namespace App\Http\Controllers\Prodi;

use App\Http\Controllers\GroceryCrudController;
use App\Models\Procurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengadaanAktifController extends GroceryCrudController
{
    public function __invoke(Request $request)
    {
        $title = "Pengadaan Aktif";
        $table = 'procurements';
        $singular = 'Pengadaan';
        $plural = 'Data Pengadaan';
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable($table);
        $crud->setSubject($singular, $plural);
        $crud->where([
            $table . '.campus_id = ?' => Auth::user()->campus_id,
            $table . '.deleted_at is null',
            $table . '.status' => Procurement::STATUS_AKTIF,
        ]);
        $crud->unsetOperations();
        $crud->setRead();
        $crud->defaultOrdering('invoice_date', 'desc');
        $crud->columns(['code']);
        // $crud->columns(['code', 'campus_id', 'publisher_id', 'approved_at']);
        $crud->readFields(['code', 'status', 'campus_id', 'publisher_id', 'approved_at', 'total_books']);
        $crud->unsetSearchColumns(['campus_id']);
        $crud->requiredFields(['campus_id']);
        $crud->setRelation('campus_id', 'campuses', 'name');
        $crud->setRelation('publisher_id', 'publishers', 'name');
        $crud->displayAs([
            'code' => 'Kode',
            'campus_id' => 'Kampus',
            'publisher_id' => 'Penerbit',
            'invoice_date' => 'Tgl. Pengadaan',
            'approved_at' => 'Tgl. Disetujui',
            'total_books' => 'Total Buku Ditawarkan',
            'total_items' => 'Total Barang',
            'total_price' => 'Total Harga',
        ]);
        $crud->callbackColumn('code', function ($value, $row) {
            return '<a href="' . route('prodi.procurements.procurement-books.active', $row->id) . '">' . $value . '</a>';
        });
        // $crud->callbackReadField('total_books', function ($value, $row) {
        //     return number_format($value, 0, ',', '.');
        // });

        $output = $crud->render();

        return $this->_showOutput($output, $title);
    }
}
