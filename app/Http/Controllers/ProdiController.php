<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdiController extends GroceryCrudController
{
    public function active_procurements()
    {
        $title = "Pengadaan Aktif";
        $table = 'invoices';
        $singular = 'Pengadaan';
        $plural = 'Data Pengadaan';
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable($table);
        $crud->setSubject($singular, $plural);
        $crud->where([
            $table . '.campus_id = ?' => Auth::user()->campus_id,
            $table . '.deleted_at is null',
            $table . '.invoice_date is not null',
            $table . '.verified_date is null',
        ]);
        $crud->unsetOperations()->setEdit();
        $crud->setRead();
        $crud->columns(['code', 'campus_id', 'publisher_note', 'campus_note', 'invoice_date']);
        $crud->addFields(['campus_id', 'publisher_note']);
        $crud->editFields(['campus_note']);
        $crud->readFields(['code', 'campus_id', 'publisher_note', 'campus_note', 'invoice_date', 'approved_at']);
        $crud->unsetSearchColumns(['campus_id']);
        $crud->requiredFields(['campus_id']);
        $crud->setTexteditor(['publisher_note', 'campus_note']);
        $crud->setRelation('campus_id', 'campuses', 'name');
        $crud->setRelation('publisher_id', 'publishers', 'name');
        $crud->displayAs([
            'code' => 'Kode',
            'campus_id' => 'Kampus',
            'publisher_id' => 'Penerbit',
            'publisher_note' => 'Catatan Penerbit',
            'campus_note' => 'Catatan Kampus',
            'invoice_date' => 'Tgl. Pengadaan',
            'approved_at' => 'Tgl. Disetujui',
        ]);
        $crud->callbackColumn('code', function ($value, $row) {
            return '<a href="' . route('prodi.procurements.books.active', $row->id) . '">' . $value . '</a>';
        });

        $output = $crud->render();

        return $this->_showOutput($output, $title);
    }

    public function procurement_books(Invoice $invoice)
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

        $crud->unsetOperations()->setEdit();
        $crud->columns(['major_id', 'title', 'published_year', 'eksemplar', 'is_chosen', 'isbn', 'author_name', 'price', 'suplemen']);
        $crud->fields(['title', 'eksemplar']);
        $crud->readFields(['title', 'eksemplar', 'is_chosen', 'major_id', 'published_year', 'isbn', 'author_name', 'price', 'suplemen']);
        $crud->requiredFields(['title', 'eksemplar']);
        $crud->setRelation('major_id', 'majors', 'name');
        $crud->fieldType('price', 'numeric');
        $crud->fieldType('is_chosen', 'checkbox_boolean');
        $crud->setRule('eksemplar', 'min', '1');
        $crud->displayAs([
            'major_id' => 'Jurusan',
            'isbn' => 'ISBN',
            'published_year' => 'Tahun Terbit',
            'author_name' => 'Nama Penulis',
            'suplemen' => 'Suplemen',
            'is_chosen' => 'Terpilih',
        ]);

        $crud->callbackBeforeUpdate(function ($s) {
            $book = Book::find($s->primaryKeyValue);
            $s->data['title'] = $book->title;

            return $s;
        });

        $crud->callbackAfterUpdate(function ($s) {
            $inv = Book::find($s->primaryKeyValue);

            if ($inv->eksemplar > 0) {
                $inv->is_chosen = 1;
            } else {
                $inv->is_chosen = 0;
            }

            $inv->save();

            return $s;
        });

        $output = $crud->render();

        return $this->_showOutput($output, $title, 'superadmin.invoice.buku');
    }

    public function archived_procurements()
    {
        # code...
    }
}
