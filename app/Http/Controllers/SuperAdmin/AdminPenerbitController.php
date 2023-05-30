<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\GroceryCrudController;
use App\Models\User;
use Illuminate\Http\Request;

class AdminPenerbitController extends GroceryCrudController
{
    public function __invoke(Request $request)
    {
        $this->authorize('manage users');
        $title = "Admin Penerbit";
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable('users');
        $crud->setSubject('Admin', 'Admin Penerbit');

        $crud->fields(['name', 'email', 'password', 'publisher_id']);
        $crud->requiredFields(['name', 'email', 'password', 'publisher_id']);
        $crud->columns(['name', 'email', 'publisher_id', 'updated_at']);

        $crud->where([
            "publisher_id is not null",
        ]);

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
                'fieldName' => 'password',
                'rule' => 'lengthMin',
                'parameters' => 8
            ],
        ]);

        $crud->callbackBeforeInsert(function ($s) {
            $s->data['password'] = bcrypt($s->data['password']);
            $s->data['email_verified_at'] = now();
            $s->data['created_at'] = now();
            $s->data['updated_at'] = now();
            return $s;
        });

        $crud->setRelation('publisher_id', 'publishers', 'name');

        $crud->displayAs([
            'name' => 'Nama PIC',
            'publisher_id'  => 'Nama Penerbit',
            'updated_at' => 'Terakhir diubah',
        ]);
        $crud->callbackAfterInsert(function ($s) {
            $user = User::find($s->insertId);
            if (!is_null($user->publisher_id) and is_null($user->campus_id) and is_null($user->major_id)) {
                $user->assignRole('Penerbit');
                $user->removeRole('Admin Prodi');
            } elseif (is_null($user->publisher_id) and !is_null($user->campus_id) and !is_null($user->major_id)) {
                $user->assignRole('Admin Prodi');
                $user->removeRole('Penerbit');
            } else {
                $user->removeRole('Admin Prodi');
                $user->removeRole('Penerbit');
            }
            return $s;
        });
        $crud->callbackAfterUpdate(function ($s) {
            $user = User::find($s->primaryKeyValue);
            if (!is_null($user->publisher_id) and is_null($user->campus_id) and is_null($user->major_id)) {
                $user->assignRole('Penerbit');
                $user->removeRole('Admin Prodi');
            } elseif (is_null($user->publisher_id) and !is_null($user->campus_id) and !is_null($user->major_id)) {
                $user->assignRole('Admin Prodi');
                $user->removeRole('Penerbit');
            } else {
                $user->removeRole('Admin Prodi');
                $user->removeRole('Penerbit');
            }
            return $s;
        });
        $crud->callbackBeforeUpdate(function ($s) {
            $s->data['updated_at'] = now();
            if ($s->data['password'] != '') {
                $s->data['password'] = bcrypt($s->data['password']);
            } else {
                unset($s->data['password']);
            }
            return $s;
        });

        $crud->callbackEditForm(function ($data) {
            $data['password'] = '';
            return $data;
        });

        $output = $crud->render();

        return $this->_showOutput($output, $title, 'grocery', 'user');
    }
}
