<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\GroceryCrudController;
use App\Models\Procurement;
use Illuminate\Http\Request;

class ArsipPengadaanController extends GroceryCrudController
{
    public function __invoke(Request $request)
    {
        $title = "Arsip Pengadaan";
        $crud = $this->_getGroceryCrudEnterprise();
        $status_invoice = Procurement::STATUS_INVOICE;
        $status_selesai = Procurement::STATUS_SELESAI;
        $status_ditolak = Procurement::STATUS_DITOLAK;

        $crud->setTable('procurements');
        $crud->setSubject('Pengadaan', 'Arsip Pengadaan');
        $crud->where([
            "procurements.deleted_at is null",
            "procurements.status in ('$status_invoice','$status_selesai','$status_ditolak')",
        ]);
        $crud->defaultOrdering('invoice_date', 'desc');
        $crud->columns(['code', 'status', 'publisher_id', 'campus_id', 'total_books', 'total_items', 'total_price']);
        $crud->setRelation('publisher_id', 'publishers', 'name');
        $crud->setRelation('campus_id', 'campuses', 'name');
        $crud->unsetOperations()->setRead();
        $crud->setFieldUpload('invoice', 'storage', asset('storage'));
        $crud->readFields(['code', 'status', 'publisher_id', 'campus_id', 'total_books', 'total_items', 'total_price', 'invoice', 'invoice_date', 'approved_at', 'verified_date', 'cancelled_date']);
        $crud->callbackColumn('code', function ($value, $row) {
            return "<a href='" . route('procurements.procurement-books.arsip', $row->id) . "'>" . $value . "</a>";
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
        $crud->displayAs([
            'code' => 'Kode',
            'created_at' => 'Status',
            'publisher_id' => 'Penerbit',
            'campus_id' => 'Kampus',
            'total_books' => 'Jumlah Buku',
            'total_items' => 'Jumlah Barang',
            'total_price' => 'Total Harga',
            'invoice_date' => 'Tgl. Pengadaan',
            'approved_at' => 'Tgl. Disetujui',
            'verified_date' => 'Tgl. Diverifikasi',
            'cancelled_date' => 'Tgl. Ditolak',
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
