<?php

namespace App\Http\Controllers\Penerbit;

use App\Http\Controllers\GroceryCrudController;
use App\Models\Procurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengadaanBaruController extends GroceryCrudController
{
    public function __invoke(Request $request)
    {
        $title = "Pengadaan Baru";
        $table = 'procurements';
        $singular = 'Pengadaan';
        $plural = 'Data Pengadaan';
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable($table);
        $crud->setSubject($singular, $plural);
        $crud->where([
            $table . '.publisher_id = ?' => Auth::user()->publisher_id,
            $table . '.deleted_at is null',
            $table . '.status' => Procurement::STATUS_PROSES,
        ]);
        $crud->defaultOrdering('invoice_date', 'desc');
        $crud->columns(['code', 'campus_id', 'invoice_date', 'total_books']);
        $crud->fields(['campus_id']);
        $crud->requiredFields(['campus_id']);
        $crud->setRelation('campus_id', 'campuses', 'name');
        $crud->displayAs([
            'code' => 'Kode',
            'campus_id' => 'Kampus',
            'invoice_date' => 'Tgl Pengadaan',
            'total_books' => 'Jumlah Buku',
        ]);
        $crud->callbackColumn('code', function ($value, $row) {
            return '<a href="' . route('penerbit.procurements.procurement-books', $row->id) . '">' . $value . '</a>';
        });
        $crud->setActionButton('Kirim Pengadaan', 'fa fa-envelope', function ($row) {
            $inv = Procurement::find($row->id);
            return route('penerbit.procurements.store', $inv->code);
        }, false);
        $crud->callbackBeforeInsert(function ($s) {
            $s->data['code'] = "BOOK-" . date('ymdHis') . "-" . str_pad(Auth::user()->publisher_id, 3, '0', STR_PAD_LEFT);
            $s->data['publisher_id'] = Auth::user()->publisher_id;
            $s->data['created_at'] = now();
            $s->data['updated_at'] = now();
            $s->data['status'] = Procurement::STATUS_PROSES;
            return $s;
        });
        $crud->callbackAfterInsert(function ($s) {
            $redirectResponse = new \GroceryCrud\Core\Redirect\RedirectResponse();
            return $redirectResponse->setUrl(route('penerbit.procurements.procurement-books', $s->insertId));
        });
        $crud->callbackDelete(function ($s) {
            $data = Procurement::find($s->primaryKeyValue);

            $data = Procurement::find($s->primaryKeyValue);

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
