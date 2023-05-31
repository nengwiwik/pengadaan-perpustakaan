<?php

namespace App\Http\Controllers\Penerbit;

use App\Http\Controllers\GroceryCrudController;
use App\Models\Procurement;
use App\Repositories\PenerbitRepository;
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
        $status_invoice = Procurement::STATUS_INVOICE;
        $status_ditolak = Procurement::STATUS_DITOLAK;
        $status_selesai = Procurement::STATUS_SELESAI;

        $crud->setTable($table);
        $crud->setSubject($singular, $plural);
        $crud->where([
            $table . '.publisher_id = ?' => Auth::user()->publisher_id,
            $table . '.deleted_at is null',
            $table . ".status in ('$status_invoice','$status_ditolak','$status_selesai')",
        ]);
        $crud->defaultOrdering('invoice_date', 'desc');
        $crud->unsetOperations()->setEdit();
        $crud->setRead();
        $crud->setFieldUpload('invoice', 'storage', asset('storage'));
        $crud->columns(['code', 'status', 'campus_id', 'invoice_date', 'total_price']);
        $crud->editFields(['invoice', 'status']);
        $crud->fieldType('status', 'hidden');
        $crud->requiredFields(['invoice']);
        $crud->setLangString('edit', 'Upload Procurement');
        $crud->callbackBeforeUpload(function ($uploadData) {
            $fieldName = $uploadData->field_name;

            $filename = isset($_FILES[$fieldName]) ? $_FILES[$fieldName]['name'] : null;

            if (!preg_match('/\.(pdf)$/', $filename)) {
                return (new \GroceryCrud\Core\Error\ErrorMessage())
                    ->setMessage("The file extension for filename: '" . $filename . "'' is not supported! Only support PDF.");
            }

            // Don't forget to return the uploadData at the end
            return $uploadData;
        });
        $crud->callbackAfterUpload(function ($data = null) {
            logger(json_encode($data));

            return $data;
        });
        $crud->callbackAfterUpdate(function ($s) {
            $procurement = Procurement::find($s->primaryKeyValue);

            if (is_null($procurement->invoice) == false && $procurement->status == Procurement::STATUS_INVOICE) {
                PenerbitRepository::sendInvoice($procurement);
                $procurement->status = Procurement::STATUS_SELESAI;
                $procurement->save();
            }

            return $s;
        });
        $crud->readFields(['code', 'status', 'campus_id', 'total_books', 'total_items', 'total_price', 'invoice', 'invoice_date', 'approved_at', 'verified_date', 'cancelled_date']);
        $crud->setRelation('campus_id', 'campuses', 'name');
        $crud->displayAs([
            'campus_id' => 'Kampus',
            'invoice_date' => 'Tgl. Penawaran',
        ]);
        $crud->callbackColumn('code', function ($value, $row) {
            return '<a href="' . route('penerbit.procurements.procurement-books.verified', $row->id) . '">' . $value . '</a>';
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
        $crud->callbackDelete(function ($s) {
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
            $status = $s->data['status'];
            if ($status != Procurement::STATUS_INVOICE) {
                $errorMessage = new \GroceryCrud\Core\Error\ErrorMessage();
                return $errorMessage->setMessage('Proses upload hanya berlaku untuk pengadaan dengan status Procurement!');
            }

            $s->data['updated_at'] = now();
            return $s;
        });

        $output = $crud->render();

        return $this->_showOutput($output, $title);
    }
}
