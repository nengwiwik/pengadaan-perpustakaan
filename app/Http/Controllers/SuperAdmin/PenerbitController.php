<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\GroceryCrudController;
use App\Models\Publisher;
use Illuminate\Http\Request;

class PenerbitController extends GroceryCrudController
{
    public function __invoke(Request $request)
    {
        $this->authorize('manage users');
        $title = "Penerbit";
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable('publishers');
        $crud->setSubject('Penerbit', 'Data Penerbit');
        $crud->where("publishers.deleted_at is null");

        $crud->fields(['code', 'name', 'address', 'email', 'phone']);
        $crud->columns(['code', 'name', 'address', 'email', 'phone']);
        $crud->requiredFields(['code', 'name', 'address', 'email', 'phone']);
        $crud->uniqueFields(['code']);

        $crud->displayAs([
            'code' => 'Kode',
            'name' => 'Nama',
            'address' => 'Alamat',
            'phone' => 'Telepon',
        ]);

        $crud->callbackBeforeInsert(function ($s) {
            $s->data['created_at'] = now();
            $s->data['updated_at'] = now();
            return $s;
        });
        $crud->callbackBeforeUpdate(function ($s) {
            $s->data['updated_at'] = now();
            return $s;
        });
        $crud->callbackDelete(function ($s) {
            $data = Publisher::find($s->primaryKeyValue);
            $data->delete();
            return $s;
        });

        $output = $crud->render();

        return $this->_showOutput($output, $title, 'grocery', 'penerbit');
    }
}
