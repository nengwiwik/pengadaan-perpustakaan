<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GroceryCrudController;
use Illuminate\Http\Request;

class RequestBukuController extends GroceryCrudController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $title = "Request Buku";
        $table = 'book_requests';
        $singular = 'Request Buku';
        $plural = 'Data Request Buku';
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable($table);
        $crud->setSubject($singular, $plural);
        $crud->where([
            $table . '.deleted_at is null',
        ]);
        $crud->setRead()->unsetDelete()->unsetAdd();
        $crud->defaultOrdering('status', 'desc');
        $crud->columns(['title', 'status', 'user_id', 'dosen_note']);
        $crud->fieldType('title', 'string');
        $crud->fieldType('author_name', 'string');
        $crud->fieldType('publisher_name', 'string');
        $crud->fieldType('price', 'numeric');
        $crud->fieldType('published_year', 'numeric');
        $crud->fields(['status', 'admin_note']);
        $crud->readFields(['title', 'isbn', 'author_name', 'published_year', 'publisher_name', 'price', 'dosen_note', 'status', 'admin_note']);
        $crud->requiredFields(['admin_note', 'status']);
        $crud->setTexteditor(['dosen_note', 'admin_note']);
        $crud->displayAs([
            'user_id' => 'Prodi',
            'title' => 'Judul Buku',
            'isbn' => 'ISBN',
            'author_name' => 'Penulis',
            'publisher_name' => 'Penerbit',
            'price' => 'Harga',
            'published_year' => 'Tahun',
            'dosen_note' => 'Catatan',
            'admin_note' => 'Catatan Admin'
        ]);
        $crud->setRelation('user_id', 'users', 'name');
        $crud->fieldType('status', 'dropdown_search', [
            'requested' => 'Request',
            'owned' => 'Sudah Dimiliki',
            'off' => 'Tidak Bisa Dipenuhi',
        ]);
        $crud->callbackReadField('price', function ($value, $row) {
            return "IDR " . number_format($value, 0, ',', '.');
        });
        $crud->callbackColumn('price', function ($value, $row) {
            return "IDR " . number_format($value, 0, ',', '.');
        });
        $crud->callbackBeforeInsert(function ($s) {
            $s->data['user_id'] = auth()->id();

            return $s;
        });

        $output = $crud->render();

        return $this->_showOutput($output, $title);
    }
}
