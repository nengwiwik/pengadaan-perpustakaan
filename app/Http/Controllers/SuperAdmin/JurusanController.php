<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\GroceryCrudController;
use App\Models\Major;
use Illuminate\Http\Request;

class JurusanController extends GroceryCrudController
{
    public function __invoke(Request $request)
    {
        $this->authorize('manage users');
        $title = "Jurusan";
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable('majors');
        $crud->setSubject('Jurusan', 'Data Jurusan');
        $crud->where("majors.deleted_at is null");

        $crud->fields(['code', 'name']);
        $crud->columns(['code', 'name']);
        $crud->requiredFields(['code', 'name']);

        $crud->displayAs([
            'code' => 'Kode',
            'name' => 'Nama',
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
            $data = Major::find($s->primaryKeyValue);
            $data->delete();
            return $s;
        });

        $output = $crud->render();

        return $this->_showOutput($output, $title, 'grocery', 'jurusan');
    }
}
