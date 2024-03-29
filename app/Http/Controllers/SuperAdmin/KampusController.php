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
        $crud->setRules([
            [
                'fieldName' => 'name',
                'rule' => 'lengthMax',
                'parameters' => 100
            ],
            [
                'fieldName' => 'email',
                'rule' => 'lengthMax',
                'parameters' => 100
            ],
            [
                'fieldName' => 'email',
                'rule' => 'emailDNS',
                'parameters' => null
            ],
            [
                'fieldName' => 'phone',
                'rule' => 'lengthMax',
                'parameters' => 20
            ],
            [
                'fieldName' => 'phone',
                'rule' => 'numeric',
                'parameters' => null
            ],
            [
                'fieldName' => 'address',
                'rule' => 'lengthMax',
                'parameters' => 100
            ],
        ]);

        // $crud->setRule('name', 'lengthMax', '100');
        // $crud->setRule('email', 'lengthMax', '100');
        // $crud->setRule('email', 'email');
        // $crud->setRule('phone', 'lengthMax', '20');
        // $crud->setRule('phone', 'numeric');
        // $crud->setRule('address', 'lengthMax', '255');

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
