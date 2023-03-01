<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\GroceryCrudController;
use App\Models\Invoice;
use Illuminate\Http\Request;

class PengadaanAktifController extends GroceryCrudController
{
    public function __invoke(Request $request)
    {
        $title = "Pengadaan Aktif";
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable('invoices');
        $crud->setSubject('Pengadaan Aktif', 'Pengadaan Aktif');
        $crud->where([
            "invoices.deleted_at is null",
            "invoices.status" => Invoice::STATUS_AKTIF,
        ]);
        $crud->defaultOrdering('invoice_date', 'desc');
        $crud->columns(['code', 'publisher_id', 'campus_id', 'total_books', 'total_items', 'total_price']);
        $crud->setRelation('publisher_id', 'publishers', 'name');
        $crud->setRelation('campus_id', 'campuses', 'name');
        $crud->fields(['campus_note'])->setTexteditor(['campus_note']);
        $crud->unsetAdd()->unsetDelete()->setRead();
        $crud->setTexteditor(['campus_note', 'publisher_note']);
        $crud->readFields(['code', 'publisher_id', 'campus_id', 'invoice_date', 'approved_at', 'campus_note', 'publisher_note', 'total_books', 'total_items', 'total_price']);
        $crud->callbackColumn('code', function ($value, $row) {
            return "<a href='" . route('procurements.books.active', $row->id) . "'>" . $value . "</a>";
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
            $data = Invoice::find($s->primaryKeyValue);
            $data->delete();
            return $s;
        });

        $output = $crud->render();

        return $this->_showOutput($output, $title, 'grocery', 'pengadaan');
    }
}
