<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\GroceryCrudController;
use App\Models\User;
use Illuminate\Http\Request;

class AdminProdiController extends GroceryCrudController
{
    public function __invoke(Request $request)
    {
        $this->authorize('manage users');
        $title = "Admin Prodi";
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable('users');
        $crud->setSubject('Admin', 'Admin Prodi');

        $crud->fields(['name', 'email', 'password', 'campus_id', 'major_id']);
        $crud->requiredFields(['name', 'email', 'password', 'campus_id', 'major_id']);
        $crud->columns(['name', 'email', 'campus_id', 'major_id', 'updated_at']);

        $crud->where([
            "campus_id is not null",
        ]);

        $crud->callbackBeforeInsert(function ($s) {
            $s->data['password'] = bcrypt($s->data['password']);
            $s->data['created_at'] = now();
            $s->data['updated_at'] = now();
            return $s;
        });

        $crud->setRelation('campus_id', 'campuses', 'name');
        $crud->setRelation('major_id', 'majors', 'name');

        $crud->displayAs([
            'name' => 'Nama',
            'campus_id'  => 'Kampus',
            'major_id' => 'Jurusan',
            'updated_at' => 'Terakhir diubah',
        ]);
        $crud->callbackBeforeInsert(function ($s) {
            $s->data['password'] = bcrypt($s->data['password']);
            $s->data['created_at'] = now();
            $s->data['updated_at'] = now();
            return $s;
        });
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