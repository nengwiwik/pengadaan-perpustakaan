<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\GroceryCrudController;
use App\Models\Procurement;
use Illuminate\Http\Request;

class PengadaanAktifController extends GroceryCrudController
{
    public function __invoke(Request $request)
    {
        $title = "Pengadaan Aktif";
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable('procurements');
        $crud->setSubject('Pengadaan Aktif', 'Pengadaan Aktif');
        $crud->where([
            "procurements.deleted_at is null",
            "procurements.status" => Procurement::STATUS_AKTIF,
        ]);
        $crud->defaultOrdering('invoice_date', 'desc');
        $crud->columns(['code', 'publisher_id', 'campus_id', 'total_books', 'total_items', 'total_price']);
        $crud->setRelation('publisher_id', 'publishers', 'name');
        $crud->setRelation('campus_id', 'campuses', 'name');
        $crud->unsetAdd()->unsetDelete()->setRead()->unsetEdit();
        $crud->readFields(['code', 'status', 'publisher_id', 'campus_id', 'invoice_date', 'approved_at',  'total_books', 'total_items', 'total_price']);
        $crud->callbackColumn('code', function ($value, $row) {
            return "<a href='" . route('procurements.procurement-books.active', $row->id) . "'>" . $value . "</a>";
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
        $crud->setActionButton('Verifikasi Pengadaan', 'fa fa-check', function ($row) {
            return route('procurement.verify', encrypt($row->id));
        }, false);
        $crud->displayAs([
            'created_at' => 'Status',
            'publisher_id' => 'Penerbit',
            'campus_id' => 'Kampus',
            'campus_note' => 'Catatan Kampus',
            'publisher_note' => 'Catatan Penerbit',
            'code' => 'Kode',
            'approved_at' => 'Tgl. Diterima',
            'invoice_date' => 'Tgl. Pengadaan',
            'total_books' => 'Jumlah Buku',
            'total_items' => 'Jumlah Barang',
            'total_price' => 'Total Harga',
        ]);

        $crud->callbackBeforeInsert(function ($s) {
            $s->data['created_at'] = now();
            $s->data['updated_at'] = now();
            return $s;
        });
        $crud->callbackBeforeUpdate(function ($s) {
            $s->data['updated_at'] = now();
            return $s;
        });
        $crud->callbackDelete(function ($s) {
            $data = Procurement::find($s->primaryKeyValue);
            $data->delete();
            return $s;
        });

        $output = $crud->render();

        return $this->_showOutput($output, $title, 'grocery', 'pengadaan');
    }
}
