<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GroceryCrudController;
use App\Models\Book;
use App\Models\Procurement;
use App\Models\Major;
use App\Traits\CalculateBooks;
use Illuminate\Http\Request;

class PengadaanAktifDetailController extends GroceryCrudController
{
    use CalculateBooks;

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

        $crud->unsetOperations()->setEdit()->setRead();
        $crud->columns(['major_id', 'cover', 'title', 'is_chosen', 'published_year', 'eksemplar', 'is_chosen', 'isbn', 'author_name', 'price', 'suplemen']);
        $crud->fields(['title', 'price', 'eksemplar', 'is_chosen']);
        $crud->readFields(['title', 'cover', 'eksemplar', 'is_chosen', 'major_id', 'published_year', 'isbn', 'author_name', 'price', 'summary', 'suplemen']);
        $crud->requiredFields(['title', 'price', 'eksemplar', 'is_chosen']);
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
        $crud->fieldType('is_chosen', 'dropdown_search', [
            1 => 'Ya',
            0 => 'Tidak',
        ]);
        $crud->setTexteditor(['summary']);
        $crud->setFieldUpload('cover', 'storage', asset('storage'));
        $crud->callbackColumn('cover', function ($value, $row) {
            $data = Book::find($row->id);
            return "<img src='" . $data->cover . "' height='150'>";
        });
        $crud->displayAs([
            'major_id' => 'Jurusan',
            'isbn' => 'ISBN',
            'published_year' => 'Tahun Terbit',
            'author_name' => 'Nama Penulis',
            'suplemen' => 'Suplemen',
            'is_chosen' => 'Pilih Buku',
        ]);
        $crud->callbackReadField('price', function ($value, $primaryKeyValue) {
            return "IDR " . number_format($value, 0, ',', '.');
        });
        $crud->callbackColumn('price', function ($value, $primaryKeyValue) {
            return "IDR " . number_format($value, 0, ',', '.');
        });
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
