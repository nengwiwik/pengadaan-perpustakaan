<?php

namespace App\Http\Controllers\Prodi;

use App\Http\Controllers\GroceryCrudController;
use App\Models\Invoice;
use Illuminate\Http\Request;

class ArsipPengadaanDetailController extends GroceryCrudController
{
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

        $crud->unsetOperations();
        $crud->columns(['major_id', 'title', 'published_year', 'eksemplar', 'price', 'is_chosen', 'isbn', 'author_name', 'suplemen']);
        $crud->readFields(['title', 'eksemplar', 'is_chosen', 'major_id', 'published_year', 'isbn', 'author_name', 'price', 'suplemen']);
        $crud->setRelation('major_id', 'majors', 'name');
        $crud->fieldType('price', 'numeric');
        $crud->fieldType('eksemplar', 'numeric');
        $crud->fieldType('is_chosen', 'checkbox_boolean');
        $crud->setRule('eksemplar', 'min', '0');
        $crud->displayAs([
            'major_id' => 'Jurusan',
            'isbn' => 'ISBN',
            'published_year' => 'Tahun Terbit',
            'author_name' => 'Nama Penulis',
            'suplemen' => 'Suplemen',
            'is_chosen' => 'Terpilih',
            'price' => 'Harga',
            'title' => 'Judul Buku',
        ]);

        $crud->callbackReadField('price', function ($value, $row) {
            return "IDR " . number_format($value, 0, ',', '.');
        });
        $crud->callbackColumn('price', function ($value, $row) {
            return "IDR " . number_format($value, 0, ',', '.');
        });

        $output = $crud->render();

        return $this->_showOutput($output, $title, 'superadmin.invoice.buku');
    }
}
