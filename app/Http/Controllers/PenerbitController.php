<?php

namespace App\Http\Controllers;

use App\Mail\NewInvoice;
use App\Models\Book;
use App\Models\Invoice;
use App\Repositories\PenerbitRepository;
use App\Traits\CalculateBooks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PenerbitController extends GroceryCrudController
{
    use CalculateBooks;

    public function ongoing_invoices()
    {
        $title = "Pengadaan Aktif";
        $table = 'invoices';
        $singular = 'Pengadaan';
        $plural = 'Data Pengadaan';
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable($table);
        $crud->setSubject($singular, $plural);
        $crud->where([
            $table . '.publisher_id = ?' => Auth::user()->publisher_id,
            $table . '.deleted_at is null',
            $table . ".status in ('" . Invoice::STATUS_AKTIF . "','" . Invoice::STATUS_BARU . "')",
        ]);
        $crud->unsetOperations();
        $crud->setRead();
        $crud->defaultOrdering('invoice_date', 'desc');
        $crud->columns(['code', 'status', 'campus_id', 'campus_note', 'invoice_date', 'total_price']);
        $crud->readFields(['code', 'status', 'campus_id', 'publisher_note', 'campus_note', 'total_books', 'total_items', 'total_price', 'invoice_date', 'approved_at']);
        $crud->requiredFields(['campus_id']);
        $crud->setTexteditor(['publisher_note', 'campus_note']);
        $crud->setRelation('campus_id', 'campuses', 'name');
        $crud->displayAs([
            'campus_id' => 'Kampus',
            'publisher_note' => 'Cat. Penerbit',
            'campus_note' => 'Cat. Kampus',
            'invoice_date' => 'Tgl. Pengadaan',
            'approved_at' => 'Tgl. Disetujui',
            'total_books' => 'Total Buku',
            'total_price' => 'Total Harga',
            'total_items' => 'Total Barang',
        ]);
        $crud->callbackColumn('code', function ($value, $row) {
            return '<a href="' . route('penerbit.invoices.books.ongoing', $row->id) . '">' . $value . '</a>';
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
        $crud->callbackBeforeInsert(function ($s) {
            $s->data['code'] = "INV-" . date('ymdHis') . "-" . str_pad(Auth::user()->publisher_id, 3, '0', STR_PAD_LEFT);
            $s->data['publisher_id'] = Auth::user()->publisher_id;
            $s->data['created_at'] = now();
            $s->data['updated_at'] = now();
            return $s;
        });
        $crud->callbackAfterInsert(function ($s) {
            $redirectResponse = new \GroceryCrud\Core\Redirect\RedirectResponse();
            return $redirectResponse->setUrl(route('penerbit.invoices.books', $s->insertId));
        });
        $crud->callbackDelete(function ($s) {
            $data = Invoice::find($s->primaryKeyValue);

            if (!$data) {
                $errorMessage = new \GroceryCrud\Core\Error\ErrorMessage();
                return $errorMessage->setMessage('Data not found');
            }

            $data->save();
            $data->delete();
            return $s;
        });
        $crud->callbackBeforeUpdate(function ($s) {
            $s->data['updated_at'] = now();
            return $s;
        });

        $output = $crud->render();

        return $this->_showOutput($output, $title);
    }

    public function ongoing_books(Invoice $invoice)
    {
        // otorisasi
        if ($invoice->publisher_id != Auth::user()->publisher_id) {
            return abort(403);
        }

        $title = "Data Buku | Nomor Pengadaan " . $invoice->code;
        $table = 'books';
        $singular = 'Buku';
        $plural = 'Data Buku | Nomor Pengadaan ' . $invoice->code;
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable($table);
        $crud->setSubject($singular, $plural);
        $crud->where([
            $table . '.invoice_id = ?' => $invoice->getKey(),
            $table . '.deleted_at is null',
        ]);

        $crud->unsetOperations();
        $crud->columns(['major_id', 'title', 'isbn', 'eksemplar', 'author_name', 'published_year', 'price', 'suplemen']);
        $crud->setRelation('major_id', 'majors', 'name');
        $crud->fieldType('price', 'numeric');
        $crud->displayAs([
            'major_id' => 'Jurusan',
            'isbn' => 'ISBN',
            'author_name' => 'Penulis',
            'published_year' => 'Tahun Terbit',
            'price' => 'Harga',
            'title' => 'Judul Buku',
        ]);
        $crud->callbackColumn('price', function ($value, $row) {
            return "IDR " . number_format($value, 0, ',', '.');
        });

        $output = $crud->render();

        return $this->_showOutput($output, $title, 'penerbit.invoice.buku-ongoing');
    }

    public function verified_invoices()
    {
        $title = "Arsip Pengadaan";
        $table = 'invoices';
        $singular = 'Pengadaan';
        $plural = 'Data Pengadaan';
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable($table);
        $crud->setSubject($singular, $plural);
        $crud->where([
            $table . '.publisher_id = ?' => Auth::user()->publisher_id,
            $table . '.deleted_at is null',
            $table . ".status in ('" . Invoice::STATUS_SELESAI . "','" . Invoice::STATUS_DITOLAK . "')",
        ]);
        $crud->defaultOrdering('invoice_date', 'desc');
        $crud->unsetOperations();
        $crud->setRead();
        $crud->columns(['code', 'status', 'campus_id', 'campus_note', 'invoice_date', 'total_price']);
        $crud->addFields(['campus_id', 'publisher_note']);
        $crud->editFields(['campus_id', 'publisher_note', 'invoice_date']);
        $crud->requiredFields(['campus_id']);
        $crud->readFields(['code', 'status', 'campus_id', 'publisher_note', 'campus_note', 'total_books', 'total_items', 'total_price', 'invoice_date', 'approved_at', 'verified_date', 'cancelled_date']);
        $crud->setTexteditor(['publisher_note', 'campus_note']);
        $crud->setRelation('campus_id', 'campuses', 'name');
        $crud->displayAs([
            'campus_id' => 'Campus',
        ]);
        $crud->callbackColumn('code', function ($value, $row) {
            return '<a href="' . route('penerbit.invoices.books.verified', $row->id) . '">' . $value . '</a>';
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
        $crud->callbackBeforeInsert(function ($s) {
            $s->data['code'] = "INV-" . date('ymdHis') . "-" . str_pad(Auth::user()->publisher_id, 3, '0', STR_PAD_LEFT);
            $s->data['publisher_id'] = Auth::user()->publisher_id;
            $s->data['created_at'] = now();
            $s->data['updated_at'] = now();
            return $s;
        });
        $crud->callbackAfterInsert(function ($s) {
            $redirectResponse = new \GroceryCrud\Core\Redirect\RedirectResponse();
            return $redirectResponse->setUrl(route('penerbit.invoices.books', $s->insertId));
        });
        $crud->callbackDelete(function ($s) {
            $data = Invoice::find($s->primaryKeyValue);

            if (!$data) {
                $errorMessage = new \GroceryCrud\Core\Error\ErrorMessage();
                return $errorMessage->setMessage('Data not found');
            }

            $data->save();
            $data->delete();
            return $s;
        });
        $crud->callbackBeforeUpdate(function ($s) {
            $s->data['updated_at'] = now();
            return $s;
        });

        $output = $crud->render();

        return $this->_showOutput($output, $title);
    }
}
