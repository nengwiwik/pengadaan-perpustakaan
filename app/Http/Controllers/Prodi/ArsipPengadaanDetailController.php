<?php

namespace App\Http\Controllers\Prodi;

use App\Http\Controllers\GroceryCrudController;
use App\Models\ProcurementBook;
use App\Models\Procurement;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArsipPengadaanDetailController extends GroceryCrudController
{
    public function __invoke(Procurement $procurement)
    {
        $title = "Data Buku | Arsip | ID Pengadaan " . $procurement->code;
        $table = 'procurement_books';
        $singular = 'Buku';
        $plural = 'Data Buku';
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable($table);
        $crud->setSubject($singular, $plural);
        $crud->where([
            $table . '.procurement_id = ?' => $procurement->getKey(),
            $table . '.deleted_at is null',
            $table . '.major_id = ?' => Auth::user()->major_id,
            // $table . '.is_chosen = ?' => 1,
        ]);

        $crud->unsetOperations();
        $crud->columns(['cover', 'title', 'published_year', 'isbn', 'author_name', 'suplemen', 'is_chosen']);
        $crud->readFields(['title'. 'cover', 'is_chosen', 'major_id', 'published_year', 'isbn', 'author_name', 'price', 'suplemen']);
        $crud->setRelation('major_id', 'majors', 'name');
        $crud->defaultOrdering('is_chosen', 'desc');
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
        $crud->setTexteditor(['summary']);
        $crud->setFieldUpload('cover', 'storage', asset('storage'));
        $crud->callbackColumn('cover', function ($value, $row) {
            $data = ProcurementBook::find($row->id);
            return "<img src='" . $data->cover . "' height='150'>";
        });
        $crud->callbackReadField('price', function ($value, $row) {
            return "IDR " . number_format($value, 0, ',', '.');
        });
        $crud->callbackColumn('price', function ($value, $row) {
            return "IDR " . number_format($value, 0, ',', '.');
        });

        $output = $crud->render();

        return $this->_showOutput($output, $title, 'prodi.arsip');
    }
}
