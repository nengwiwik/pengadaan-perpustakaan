<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GroceryCrudController;
use App\Models\Book;
use App\Models\Invoice;
use App\Traits\CalculateBooks;
use Illuminate\Http\Request;

class PengadaanAktifDetailController extends GroceryCrudController
{
    use CalculateBooks;

    public function __invoke(Invoice $invoice)
    {
        $title = "Data Buku | ID Pengadaan " . $invoice->code;
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

        $crud->unsetOperations()->setEdit()->setRead();
        $crud->columns(['major_id', 'title', 'published_year', 'eksemplar', 'is_chosen', 'isbn', 'author_name', 'price', 'suplemen']);
        $crud->fields(['title', 'price', 'eksemplar']);
        $crud->readFields(['title', 'eksemplar', 'major_id', 'published_year', 'isbn', 'author_name', 'price', 'suplemen']);
        $crud->requiredFields(['title', 'price', 'eksemplar']);
        $crud->setRelation('major_id', 'majors', 'name');
        $crud->fieldType('price', 'numeric');
        $crud->fieldType('is_chosen', 'checkbox_boolean');
        $crud->displayAs([
            'major_id' => 'Jurusan',
            'isbn' => 'ISBN',
            'published_year' => 'Tahun Terbit',
            'author_name' => 'Nama Penulis',
            'suplemen' => 'Suplemen',
            'is_chosen' => 'Pilih Buku',
        ]);
        $crud->callbackBeforeUpdate(function ($s) {
            $book = Book::find($s->primaryKeyValue);
            $s->data['title'] = $book->title;
            $s->data['price'] = $book->price;

            return $s;
        });
        $crud->callbackAfterUpdate(function ($s) {
            $inv = Book::find($s->primaryKeyValue);

            if ($inv->eksemplar > 0) {
                $inv->is_chosen = 1;
                $this->calculatePrice($inv->invoice);
            } else {
                $inv->eksemplar = null;
                $inv->is_chosen = 0;
            }

            $inv->save();

            return $s;
        });

        $output = $crud->render();

        return $this->_showOutput($output, $title, 'superadmin.invoice.buku');
    }
}
