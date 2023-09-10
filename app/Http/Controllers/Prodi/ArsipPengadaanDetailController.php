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
            $table . '.is_chosen = ?' => 1,
        ]);

        $crud->unsetOperations();
        $crud->columns(['title', 'published_year', 'isbn', 'author_name', 'price', 'eksemplar', 'suplemen']);
        $crud->readFields(['title', 'summary', 'is_chosen', 'published_year', 'isbn', 'author_name', 'price', 'suplemen']);
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
        $crud->fieldType('is_chosen', 'dropdown_search', [
            1 => 'Ya',
            0 => 'Tidak',
        ]);
        $crud->setTexteditor(['summary']);
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
