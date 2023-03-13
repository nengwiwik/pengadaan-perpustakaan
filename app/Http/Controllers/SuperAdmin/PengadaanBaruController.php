<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\GroceryCrudController;
use App\Models\Invoice;
use Illuminate\Http\Request;

class PengadaanBaruController extends GroceryCrudController
{
    public function __invoke(Request $request)
    {
        $title = "Pengadaan Baru";
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable('invoices');
        $crud->setSubject('Pengadaan Baru', 'Data Pengadaan Baru');
        $crud->where([
            "invoices.invoice_date is not null",
            "invoices.status" => Invoice::STATUS_BARU,
        ]);
        $crud->defaultOrdering('invoice_date', 'desc');
        $crud->columns(['status', 'code', 'publisher_id', 'campus_id', 'cancelled_date', 'approved_at']);
        $crud->fieldTypeColumn('cancelled_date', 'invisible');
        $crud->fieldTypeColumn('approved_at', 'invisible');
        $crud->setRelation('publisher_id', 'publishers', 'name');
        $crud->setRelation('campus_id', 'campuses', 'name');
        $crud->unsetAdd()->unsetDelete()->setRead()->unsetReadFields(['deleted_at', 'updated_at'])->unsetEdit();
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
            return "<a href='" . route('procurements.books', $row->id) . "'>" . $value . "</a>";
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
