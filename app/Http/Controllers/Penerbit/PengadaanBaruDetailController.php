<?php

namespace App\Http\Controllers\Penerbit;

use App\Http\Controllers\GroceryCrudController;
use App\Models\Book;
use App\Models\Invoice;
use App\Traits\CalculateBooks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengadaanBaruDetailController extends GroceryCrudController
{
    use CalculateBooks;

    public function __invoke(Invoice $invoice)
    {
        // otorisasi
        if ($invoice->publisher_id != Auth::user()->publisher_id) {
            return abort(403);
        }

        $title = "Data Buku | Nomor Pengadaan " . $invoice->code;
        $table = 'books';
        $singular = 'Buku';
        $plural = 'Data Buku';
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable($table);
        $crud->setSubject($singular, $plural);
        $crud->where([
            $table . '.invoice_id = ?' => $invoice->getKey(),
            $table . '.deleted_at is null',
        ]);

        $crud->setRead();
        $crud->columns(['major_id', 'title', 'isbn', 'author_name', 'published_year', 'price', 'suplemen']);
        $crud->fields(['major_id', 'title', 'isbn', 'author_name', 'published_year', 'price', 'suplemen']);
        $crud->requiredFields(['major_id', 'title', 'isbn', 'author_name', 'published_year', 'price', 'suplemen']);
        $crud->setRelation('major_id', 'majors', 'name');
        $crud->fieldType('price', 'numeric');
        $crud->displayAs([
            'major_id' => 'Jurusan',
            'isbn' => 'ISBN',
            'suplemen' => 'Suplemen',
            'author_name' => 'Penulis',
            'published_year' => 'Tahun Terbit',
            'price' => 'Harga',
            'title' => 'Judul Buku',
        ]);
        $crud->callbackBeforeInsert(function ($s) use ($invoice) {
            $s->data['invoice_id'] = $invoice->getKey();
            $s->data['created_at'] = now();
            $s->data['updated_at'] = now();
            return $s;
        });
        $crud->callbackAfterInsert(function ($s) {
            $inv = Book::find($s->insertId);
            $this->calculateBooks($inv->invoice);

            return $s;
        });
        $crud->callbackAfterUpdate(function ($s) {
            $inv = Book::find($s->primaryKeyValue);
            $this->calculateBooks($inv->invoice);

            return $s;
        });
        $crud->callbackDelete(function ($s) {
            $data = Book::find($s->primaryKeyValue);

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

        return $this->_showOutput($output, $title, 'penerbit.invoice.buku');
    }
}
