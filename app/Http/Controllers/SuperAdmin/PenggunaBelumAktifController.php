<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\GroceryCrudController;
use App\Models\User;
use Illuminate\Http\Request;

class PenggunaBelumAktifController extends GroceryCrudController
{
    public function __invoke(Request $request)
    {
        $this->authorize('manage users');
        $title = "Pengguna Belum Aktif";
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable('users');
        $crud->setSubject('User', 'Pengguna Belum Aktif');

        $crud->unsetAdd();
        $crud->fields(['name', 'campus_id', 'major_id']);
        $crud->requiredFields(['name', 'campus_id', 'major_id']);
        $crud->columns(['name', 'email', 'updated_at']);

        $crud->where([
            "publisher_id is null",
            "campus_id is null",
            "email != ?" => config('undira.admin_email'),
        ]);

        $crud->callbackBeforeInsert(function ($s) {
            $s->data['created_at'] = now();
            $s->data['updated_at'] = now();
            return $s;
        });

        $crud->setRelation('major_id', 'majors', 'name');
        $crud->setRelation('campus_id', 'campuses', 'name');

        $crud->displayAs([
            'name' => 'Nama',
            'updated_at' => 'Terakhir diubah',
            'major_id'  => 'Jurusan',
            'campus_id'  => 'Kampus',
        ]);
        $crud->callbackAfterUpdate(function ($s) {
            $user = User::find($s->primaryKeyValue);
            if (!is_null($user->campus_id) and !is_null($user->major_id)) {
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
            return $s;
        });

        $output = $crud->render();

        return $this->_showOutput($output, $title, 'grocery', 'user');
    }
}
