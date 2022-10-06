<?php

namespace App\Http\Controllers;

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
    $crud->callbackBeforeInsert(function ($s) {
      $s->data['code'] = "INV-" . date('ymdHis') . "-" . str_pad(Auth::user()->publisher_id, 3, '0', STR_PAD_LEFT);
      $s->data['publisher_id'] = Auth::user()->publisher_id;
      $s->data['created_at'] = now();
      $s->data['updated_at'] = now();
      return $s;
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
