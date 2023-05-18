<?php

namespace App\Http\Controllers\Prodi;

use App\Http\Controllers\GroceryCrudController;
use App\Models\Book;
use App\Models\Procurement;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArsipPengadaanDetailController extends GroceryCrudController
{
    public function __invoke(Procurement $procurement)
    {
        $title = "Data Buku | ID Pengadaan " . $procurement->code;
        $table = 'books';
        $singular = 'Buku';
        $plural = 'Data Buku';
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable($table);
        $crud->setSubject($singular, $plural);
        $crud->where([
            $table . '.invoice_id = ?' => $procurement->getKey(),
            $table . '.deleted_at is null',
        ]);

        $crud->unsetOperations();
        $crud->columns(['major_id', 'cover', 'title', 'published_year', 'eksemplar', 'price', 'is_chosen', 'isbn', 'author_name', 'suplemen']);
        $crud->readFields(['title'. 'cover', 'eksemplar', 'is_chosen', 'major_id', 'published_year', 'isbn', 'author_name', 'price', 'suplemen']);
        // $crud->setRelation('major_id', 'majors', 'name');
        $crud->fieldType('major_id', 'multiselect_searchable', Major::get()->pluck('name'));
        $crud->callbackReadField('major_id', function ($fieldValue, $primaryKeyValue) {
            $last_major = array_key_last($fieldValue);
            $res = "";
            $data_majors = Major::all();
            foreach ($data_majors as $key => $dmajor) {
                foreach ($fieldValue as $k => $major) {
                    if ($key == $major) {
                        $res .= $dmajor->name;
                        if ($k != $last_major) $res .= ", ";
                    }
                }
            }
            return $res;
        });
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
            $data = Book::find($row->id);
            return "<img src='" . $data->cover . "' height='150'>";
        });
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
