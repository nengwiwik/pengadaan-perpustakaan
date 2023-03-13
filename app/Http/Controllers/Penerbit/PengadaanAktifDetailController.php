<?php

namespace App\Http\Controllers\Penerbit;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GroceryCrudController;
use App\Models\Book;
use App\Models\Invoice;
use App\Traits\CalculateBooks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengadaanAktifDetailController extends GroceryCrudController
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
        $plural = 'Data Buku | Nomor Pengadaan ' . $invoice->code;
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable($table);
        $crud->setSubject($singular, $plural);
        $crud->where([
            $table . '.invoice_id = ?' => $invoice->getKey(),
            $table . '.deleted_at is null',
        ]);

        $crud->unsetOperations()->setRead();
        $crud->columns(['major_id', 'cover', 'title', 'isbn', 'eksemplar', 'price']);
        $crud->fields(['major_id', 'cover', 'title', 'isbn', 'eksemplar', 'author_name', 'published_year', 'price', 'summary', 'suplemen']);
        $crud->setRelation('major_id', 'majors', 'name');
        $crud->fieldType('price', 'numeric');
        $crud->displayAs([
            'major_id' => 'Jurusan',
            'isbn' => 'ISBN',
            'author_name' => 'Penulis',
            'published_year' => 'Tahun Terbit',
            'price' => 'Harga',
            'title' => 'Judul Buku',
        ]);
        $crud->setTexteditor(['summary']);
        $crud->setFieldUpload('cover', 'storage', asset('storage'));
        $crud->callbackColumn('cover', function ($value, $row) {
            $data = Book::find($row->id);
            return "<img src='" . $data->cover . "' height='150'>";
        });
        $crud->callbackReadField('price', function ($value, $primaryKeyValue) {
            return "IDR " . number_format($value, 0, ',', '.');
        });
        $crud->callbackColumn('price', function ($value, $primaryKeyValue) {
            return "IDR " . number_format($value, 0, ',', '.');
        });

        $output = $crud->render();

        return $this->_showOutput($output, $title, 'penerbit.invoice.buku-ongoing');
    }
}
