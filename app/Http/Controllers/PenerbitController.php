<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenerbitController extends GroceryCrudController
{
  public function invoices()
  {
    $title = "Invoices";
    $table = 'invoices';
    $singular = 'Invoice';
    $plural = 'Invoices';
    $crud = $this->_getGroceryCrudEnterprise();

    $crud->setTable($table);
    $crud->setSubject($singular, $plural);
    $crud->where([
      $table . '.publisher_id = ?' => Auth::user()->publisher_id,
      $table . '.deleted_at is null',
    ]);

    $crud->setRead();
    $crud->columns(['code', 'campus_id', 'publisher_note', 'campus_note', 'invoice_date']);
    $crud->addFields(['campus_id', 'publisher_note']);
    $crud->editFields(['campus_id', 'publisher_note', 'invoice_date']);
    $crud->requiredFields(['campus_id']);
    $crud->setTexteditor(['publisher_note', 'campus_note']);
    $crud->setRelation('campus_id', 'campuses', 'name');
    $crud->displayAs([
      'campus_id' => 'Campus',
    ]);
    $crud->setActionButton('Books', 'fa fa-book', function ($row) {
      return route('penerbit.invoices.books', $row->id);
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

  public function books(Invoice $invoice)
  {
    // otorisasi
    if ($invoice->publisher_id != Auth::user()->publisher_id) {
      return abort(403);
    }

    $title = "Books | Invoice " . $invoice->code;
    $table = 'books';
    $singular = 'Book';
    $plural = 'Books';
    $crud = $this->_getGroceryCrudEnterprise();

    $crud->setTable($table);
    $crud->setSubject($singular, $plural);
    $crud->where([
      $table . '.invoice_id = ?' => $invoice->getKey(),
      $table . '.deleted_at is null',
    ]);

    $crud->setRead();
    $crud->columns(['major_id', 'title', 'isbn', 'author_name', 'published_year', 'price', 'suplemen']);
    $crud->fields(['major_id', 'title', 'isbn', 'author_name', 'published_year', 'price', 'suplemen']);
    $crud->requiredFields(['major_id', 'title', 'isbn', 'author_name', 'published_year', 'price', 'suplemen']);
    $crud->setRelation('major_id', 'majors', 'name');
    $crud->fieldType('price', 'numeric');
    $crud->displayAs([
      'major_id' => 'Major',
      'isbn' => 'ISBN',
      'suplemen' => 'Suplement',
    ]);
    $crud->callbackBeforeInsert(function ($s) use ($invoice) {
      $s->data['invoice_id'] = $invoice->getKey();
      $s->data['created_at'] = now();
      $s->data['updated_at'] = now();
      return $s;
    });
    $crud->callbackDelete(function ($s) {
      $data = Book::find($s->primaryKeyValue);

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

    return $this->_showOutput($output, $title, 'penerbit.invoice.buku');
  }
}
