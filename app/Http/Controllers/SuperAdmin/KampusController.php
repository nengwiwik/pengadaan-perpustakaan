<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\GroceryCrudController;
use App\Models\Campus;
use Illuminate\Http\Request;

class KampusController extends GroceryCrudController
{
    public function __invoke(Request $request)
    {
        $this->authorize('manage users');
        $title = "Kampus";
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable('campuses');
        $crud->setSubject('Kampus', 'Data Kampus');
        $crud->where("campuses.deleted_at is null");

        $crud->fields(['name', 'address', 'email', 'phone']);
        $crud->columns(['name', 'address', 'email', 'phone']);
        $crud->requiredFields(['name', 'address', 'email', 'phone']);

        // validasi
        $crud->setRule('name', 'lengthMax', '100');
        $crud->setRule('email', 'lengthMax', '100');
        $crud->setRule('phone', 'lengthMax', '20');
        $crud->setRule('address', 'lengthMax', '255');

        $crud->displayAs([
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
            $data = Campus::find($s->primaryKeyValue);
            $data->delete();
            return $s;
        });

        $output = $crud->render();

        return $this->_showOutput($output, $title, 'grocery', 'kampus');
    }
}
