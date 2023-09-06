<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\GroceryCrudController;
use App\Models\Procurement;
use App\Traits\CalculateBooks;
use Illuminate\Http\Request;

class PengadaanBaruController extends GroceryCrudController
{
    use CalculateBooks;

    public function __invoke(Request $request)
    {
        $title = "Pengadaan Baru";
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable('procurements');
        $crud->setSubject('Pengadaan Baru', 'Data Pengadaan Baru');
        $crud->where([
            "procurements.invoice_date is not null",
            "procurements.status" => Procurement::STATUS_BARU,
        ]);
        $crud->defaultOrdering('invoice_date', 'desc');
        $crud->columns(['status', 'code', 'publisher_id', 'campus_id', 'budget', 'cancelled_date', 'approved_at']);
        $crud->editFields(['budget']);
        $crud->requiredFields(['budget']);
        $crud->fieldTypeColumn('cancelled_date', 'invisible');
        $crud->fieldTypeColumn('approved_at', 'invisible');
        $crud->setRelation('publisher_id', 'publishers', 'name');
        $crud->setRelation('campus_id', 'campuses', 'name');
        $crud->unsetAdd()->unsetDelete()->setRead()->unsetReadFields(['deleted_at', 'updated_at']);
        $crud->fieldType('budget', 'int');
        $crud->setLangString('edit', 'Budget');
        $crud->setLangString('edit_item', 'Atur Budget Pengadaan');
        $crud->setRules([
            [
                'fieldName' => 'budget',
                'rule' => 'numeric',
                'parameters' => ''
            ],
            [
                'fieldName' => 'budget',
                'rule' => 'min',
                'parameters' => '10000'
            ],
        ]);
        $crud->callbackColumn('status', function ($value, $row) {
            if (is_null($row->approved_at) && is_null($row->cancelled_date)) {
                return "Pending";
            }
            if (!is_null($row->approved_at)) {
                return "Approved";
            }
            if (!is_null($row->cancelled_date)) {
                return "Rejected";
            }
        });
        $crud->callbackColumn('code', function ($value, $row) {
            return "<a href='" . route('procurements.procurement-books', $row->id) . "'>" . $value . "</a>";
        });
        $crud->callbackColumn('budget', function ($value, $row) {
            return 'Rp ' . number_format($value, 0, ',', '.');
        });
        $crud->setActionButton('Terima Permintaan', 'fa fa-check', function ($row) {
            return route('procurement.approve', encrypt($row->id));
        }, false);
        $crud->setActionButton('Tolak Permintaan', 'fa fa-times', function ($row) {
            return route('procurement.reject', encrypt($row->id));
        }, false);
        $crud->displayAs([
            'code' => 'Kode',
            'created_at' => 'Status',
            'publisher_id' => 'Penerbit',
            'campus_id' => 'Kampus',
            'invoice_date' => 'Tanggal Invoice',
            'approved_date' => 'Tanggal Diterima',
            'verified_date' => 'Tanggal Diverifikasi',
            'paid_date' => 'Tanggal Pembayaran',
            'cancelled_date' => 'Tanggal Dibatalkan',
            'total_books' => 'Jumlah Buku',
            'total_items' => 'Total Barang',
            'total_price' => 'Total Biaya',
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
            $data->procurement_books()->delete();
            $this->calculateBooks($data);
            $data->delete();
            return $s;
        });

        $output = $crud->render();

        return $this->_showOutput($output, $title, 'grocery', 'pengadaan');
    }
}
