<?php

namespace App\Http\Controllers\Prodi;

use App\Http\Controllers\GroceryCrudController;
use App\Models\Procurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArsipPengadaanController extends GroceryCrudController
{
    public function __invoke(Request $request)
    {
        $title = "Arsip Pengadaan";
        $table = 'procurements';
        $singular = 'Pengadaan';
        $plural = 'Data Pengadaan';
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable($table);
        $crud->setSubject($singular, $plural);
        $crud->where([
            $table . '.campus_id = ?' => Auth::user()->campus_id,
            $table . '.deleted_at is null',
            $table . ".status in ('" . Procurement::STATUS_SELESAI . "','" . Procurement::STATUS_INVOICE . "')",
        ]);
        $crud->unsetOperations();
        $crud->setRead();
        $crud->defaultOrdering('invoice_date', 'desc');
        $crud->setFieldUpload('invoice', 'storage', asset('storage'));
        $crud->columns(['code', 'publisher_id', 'budget', 'total_price']);
        $crud->readFields(['code', 'campus_id', 'publisher_id', 'budget', 'total_books', 'total_items', 'total_price', 'invoice', 'final_price', 'invoice_date', 'approved_at', 'verified_date', 'cancelled_date']);
        $crud->unsetSearchColumns(['campus_id']);
        $crud->setRelation('campus_id', 'campuses', 'name');
        $crud->setRelation('publisher_id', 'publishers', 'name');
        $crud->displayAs([
            'code' => 'Kode',
            'budget' => 'Anggaran Biaya',
            'campus_id' => 'Kampus',
            'publisher_id' => 'Penerbit',
            'invoice_date' => 'Tgl. Pengadaan',
            'approved_at' => 'Tgl. Disetujui',
            'verified_date' => 'Tgl. Verifikasi',
            'cancelled_date' => 'Tgl. Ditolak',
            'total_books' => 'Total Buku',
            'total_items' => 'Total Barang',
            'total_price' => 'Total Harga',
            'final_price' => 'Harga Final',
        ]);
        $crud->callbackColumn('code', function ($value, $row) {
            return '<a href="' . route('prodi.procurements.procurement-books.archived', $row->id) . '">' . $value . '</a>';
        });
        $crud->callbackReadField('total_books', function ($value, $row) {
            return number_format($value, 0, ',', '.');
        });
        $crud->callbackReadField('total_items', function ($value, $row) {
            return number_format($value, 0, ',', '.');
        });
        $crud->callbackReadField('total_price', function ($value, $row) {
            return "IDR " . number_format($value, 0, ',', '.');
        });
        $crud->callbackColumn('total_price', function ($value, $row) {
            return "IDR " . number_format($value, 0, ',', '.');
        });
        $crud->callbackReadField('budget', function ($value, $row) {
            return "IDR " . number_format($value, 0, ',', '.');
        });
        $crud->callbackReadField('final_price', function ($value, $row) {
            return "IDR " . number_format($value, 0, ',', '.');
        });
        $crud->callbackColumn('budget', function ($value, $row) {
            return "IDR " . number_format($value, 0, ',', '.');
        });
        $crud->setFieldUpload('invoice', 'storage', asset('storage'));

        $output = $crud->render();

        return $this->_showOutput($output, $title);
    }
}
