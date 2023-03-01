<?php

namespace App\Http\Controllers\Penerbit;

use App\Http\Controllers\GroceryCrudController;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengadaanBaruController extends GroceryCrudController
{
    public function __invoke(Request $request)
    {
        $title = "Pengadaan Baru";
        $table = 'invoices';
        $singular = 'Pengadaan';
        $plural = 'Data Pengadaan';
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable($table);
        $crud->setSubject($singular, $plural);
        $crud->where([
            $table . '.publisher_id = ?' => Auth::user()->publisher_id,
            $table . '.deleted_at is null',
            $table . '.status' => Invoice::STATUS_PROSES,
        ]);
        $crud->defaultOrdering('invoice_date', 'desc');
        $crud->columns(['code', 'campus_id', 'publisher_note', 'invoice_date', 'total_books']);
        $crud->addFields(['campus_id', 'publisher_note']);
        $crud->editFields(['campus_id', 'publisher_note', 'invoice_date']);
        $crud->requiredFields(['campus_id']);
        $crud->setTexteditor(['publisher_note']);
        $crud->setRelation('campus_id', 'campuses', 'name');
        $crud->displayAs([
            'code' => 'Kode',
            'campus_id' => 'Kampus',
            'invoice_date' => 'Tgl Pengadaan',
            'publisher_note' => 'Catatan Penerbit',
            'total_books' => 'Jumlah Buku',
        ]);
        $crud->callbackColumn('code', function ($value, $row) {
            return '<a href="' . route('penerbit.invoices.books', $row->id) . '">' . $value . '</a>';
        });
        $crud->callbackBeforeInsert(function ($s) {
            $s->data['code'] = "INV-" . date('ymdHis') . "-" . str_pad(Auth::user()->publisher_id, 3, '0', STR_PAD_LEFT);
            $s->data['publisher_id'] = Auth::user()->publisher_id;
            $s->data['created_at'] = now();
            $s->data['updated_at'] = now();
            $s->data['status'] = Invoice::STATUS_PROSES;
            return $s;
        });
        $crud->callbackAfterInsert(function ($s) {
            $redirectResponse = new \GroceryCrud\Core\Redirect\RedirectResponse();
            return $redirectResponse->setUrl(route('penerbit.invoices.books', $s->insertId));
        });
        $crud->callbackDelete(function ($s) {
            $data = Invoice::find($s->primaryKeyValue);

            if (!$data) {
                $errorMessage = new \GroceryCrud\Core\Error\ErrorMessage();
                return $errorMessage->setMessage('Data not found');
            }

            $data->save();
            $data->delete();
            return $s;
        });
        $crud->callbackBeforeUpdate(function ($s) {
            $s->data['updated_at'] = now();
            return $s;
        });
        $crud->callbackAfterUpdate(function ($s) {
            $invoice = Invoice::find($s->primaryKeyValue);

            if (is_null($invoice->invoice_date) == false) {
                // PenerbitRepository::sendEmails($invoice);
                $invoice->status = Invoice::STATUS_BARU;
                $invoice->save();
            }

            return $s;
        });

        $output = $crud->render();

        return $this->_showOutput($output, $title);
    }
}