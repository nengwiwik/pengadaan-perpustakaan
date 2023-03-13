<?php

namespace App\Http\Controllers\Penerbit;

use App\Http\Controllers\GroceryCrudController;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArsipPengadaanController extends GroceryCrudController
{
    public function __invoke(Request $request)
    {
        $title = "Arsip Pengadaan";
        $table = 'invoices';
        $singular = 'Pengadaan';
        $plural = 'Data Pengadaan';
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable($table);
        $crud->setSubject($singular, $plural);
        $crud->where([
            $table . '.publisher_id = ?' => Auth::user()->publisher_id,
            $table . '.deleted_at is null',
            $table . ".status in ('" . Invoice::STATUS_SELESAI . "','" . Invoice::STATUS_DITOLAK . "')",
        ]);
        $crud->defaultOrdering('invoice_date', 'desc');
        $crud->unsetOperations();
        $crud->setRead();
        $crud->columns(['code', 'status', 'campus_id', 'invoice_date', 'total_price']);
        $crud->addFields(['campus_id']);
        $crud->editFields(['campus_id', 'invoice_date']);
        $crud->requiredFields(['campus_id']);
        $crud->readFields(['code', 'status', 'campus_id', 'total_books', 'total_items', 'total_price', 'invoice_date', 'approved_at', 'verified_date', 'cancelled_date']);
        $crud->setRelation('campus_id', 'campuses', 'name');
        $crud->displayAs([
            'campus_id' => 'Kampus',
            'invoice_date' => 'Tgl. Penawaran',
        ]);
        $crud->callbackColumn('code', function ($value, $row) {
            return '<a href="' . route('penerbit.invoices.books.verified', $row->id) . '">' . $value . '</a>';
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
        $crud->callbackBeforeInsert(function ($s) {
            $s->data['code'] = "INV-" . date('ymdHis') . "-" . str_pad(Auth::user()->publisher_id, 3, '0', STR_PAD_LEFT);
            $s->data['publisher_id'] = Auth::user()->publisher_id;
            $s->data['created_at'] = now();
            $s->data['updated_at'] = now();
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

        $output = $crud->render();

        return $this->_showOutput($output, $title);
    }
}
