<?php

namespace App\Http\Controllers\Penerbit;

use App\Http\Controllers\GroceryCrudController;
use App\Models\ProcurementBook;
use App\Models\Procurement;
use App\Models\Major;
use Illuminate\Http\Request;

class ArsipPengadaanDetailController extends GroceryCrudController
{
    public function __invoke(Procurement $procurement)
    {
        $title = "Data Buku | ID Pengadaan " . $procurement->code;
        $table = 'books';
        $singular = 'Buku';
        $plural = $title;
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable($table);
        $crud->setSubject($singular, $plural);
        $crud->where([
            $table . '.procurement_id = ?' => $procurement->getKey(),
            // $table . '.deleted_at is null',
            // $table . '.is_chosen = ?' => 1,
        ]);

        $crud->unsetOperations();
        $crud->columns(['title', 'eksemplar', 'price', 'published_year', 'isbn', 'author_name', 'suplemen']);
        $crud->fieldType('price', 'numeric');
        $crud->displayAs([
            'major_id' => 'Jurusan',
            'isbn' => 'ISBN',
            'published_year' => 'Tahun Terbit',
            'author_name' => 'Nama Penulis',
            'title' => 'Judul Buku',
            'suplemen' => 'Suplemen',
            'price' => 'Harga',
        ]);
        $crud->setTexteditor(['summary']);
        $crud->setFieldUpload('cover', 'storage', asset('storage'));
        $crud->callbackColumn('cover', function ($value, $row) {
            $data = ProcurementBook::find($row->id);
            return "<img src='" . $data->cover . "' height='150'>";
        });
        $crud->callbackReadField('price', function ($value, $row) {
            return "IDR " . number_format($value, 0, ',', '.');
        });
        // $crud->callbackColumn('price', function ($value, $row) {
        //     return "IDR " . number_format($value, 0, ',', '.');
        // });

        $output = $crud->render();

        return $this->_showOutput($output, $title, 'superadmin.invoice.buku');
    }
}
