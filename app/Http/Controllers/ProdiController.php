<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Invoice;
use App\Traits\CalculateBooks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdiController extends GroceryCrudController
{
    use CalculateBooks;

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
            $table . '.approved_at is not null',
            $table . '.verified_date is null',
        ]);
        $crud->unsetOperations()->setEdit();
        $crud->setRead();
        $crud->columns(['code', 'campus_id', 'publisher_note', 'campus_note', 'total_price']);
        $crud->addFields(['campus_id', 'publisher_note']);
        $crud->editFields(['campus_note']);
        $crud->readFields(['code', 'campus_id', 'publisher_id', 'publisher_note', 'campus_note', 'invoice_date', 'approved_at', 'total_books', 'total_items', 'total_price']);
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
            'total_books' => 'Total Buku',
            'total_items' => 'Total Barang',
            'total_price' => 'Total Harga',
        ]);
        $crud->callbackColumn('code', function ($value, $row) {
            return '<a href="' . route('prodi.procurements.books.active', $row->id) . '">' . $value . '</a>';
        });
        $crud->callbackReadField('total_books', function ($value, $row) {
            return number_format($value, 0, ',', '.');
        });
        $crud->callbackReadField('total_items', function ($value, $row) {
            return number_format($value, 0, ',', '.');
        });
        $crud->callbackReadField('total_price', function ($value, $row) {
            return "IDR " . number_format($value, 0, ',', '.');
        });
        $crud->callbackColumn('total_price', function ($value, $row) {
            return "IDR " . number_format($value, 0, ',', '.');
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
        $crud->columns(['major_id', 'title', 'published_year', 'eksemplar', 'price', 'is_chosen', 'isbn', 'author_name', 'suplemen']);
        $crud->fields(['title', 'eksemplar', 'price']);
        $crud->readFields(['title', 'eksemplar', 'is_chosen', 'major_id', 'published_year', 'isbn', 'author_name', 'price', 'suplemen']);
        $crud->requiredFields(['title', 'eksemplar']);
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

    public function archived_procurements()
    {
        # code...
    }
}
