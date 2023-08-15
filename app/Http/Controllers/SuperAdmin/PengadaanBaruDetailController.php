<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\GroceryCrudController;
use App\Models\ProcurementBook;
use App\Models\Procurement;
use App\Models\Major;
use App\Traits\CalculateBooks;
use Illuminate\Http\Request;

class PengadaanBaruDetailController extends GroceryCrudController
{
    use CalculateBooks;

    public function __invoke(Procurement $procurement)
    {
        $title = "Data Buku | ID Pengadaan " . $procurement->code;
        $table = 'procurement_books';
        $singular = 'Buku';
        $plural = 'Data Buku';
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable($table);
        $crud->setSubject($singular, $plural);
        $crud->where([
            $table . '.procurement_id = ?' => $procurement->getKey(),
            $table . '.deleted_at is null',
        ]);

        $crud->unsetOperations()->setDelete()->setRead();
        $crud->columns(['major_id', 'cover', 'title', 'published_year', 'isbn', 'author_name', 'price', 'suplemen']);
        $crud->fields(['major_id', 'cover', 'title', 'published_year', 'isbn', 'author_name', 'price', 'summary', 'suplemen']);
        $crud->setRelation('major_id', 'majors', 'name');
        // $crud->fieldType('major_id', 'multiselect_searchable', Major::get()->pluck('name'));
        // $crud->callbackReadField('major_id', function ($fieldValue, $primaryKeyValue) {
        //     $last_major = array_key_last($fieldValue);
        //     $res = "";
        //     $data_majors = Major::all();
        //     foreach ($data_majors as $key => $dmajor) {
        //         foreach ($fieldValue as $k => $major) {
        //             if ($key == $major) {
        //                 $res .= $dmajor->name;
        //                 if ($k != $last_major) $res .= ", ";
        //             }
        //         }
        //     }
        //     return $res;
        // });
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
        $crud->callbackReadField('cover', function ($fieldValue, $primaryKeyValue) {
            $data = ProcurementBook::find($primaryKeyValue);
            return "<img src='" . $data->cover . "' height='150'>";
        });
        $crud->callbackReadField('price', function ($value, $row) {
            return "IDR " . number_format($value, 0, ',', '.');
        });
        $crud->callbackColumn('price', function ($value, $row) {
            return "IDR " . number_format($value, 0, ',', '.');
        });
        $crud->callbackDelete(function ($s) {
            $data = ProcurementBook::find($s->primaryKeyValue);
            ProcurementBook::where([
                'procurement_id' => $data->procurement_id,
                'isbn' => $data->isbn,
            ])->delete();
            $this->calculateBooks($data->procurement);
            return $s;
        });

        $output = $crud->render();

        return $this->_showOutput($output, $title, 'superadmin.invoice.buku');
    }
}
