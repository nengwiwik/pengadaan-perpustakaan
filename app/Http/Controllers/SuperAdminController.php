<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\Major;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminController extends GroceryCrudController
{
  public function users()
  {
    $this->authorize('manage users');
    $title = "Users";
    $crud = $this->_getGroceryCrudEnterprise();

    $crud->setTable('users');
    $crud->setSubject('User', 'Users');

    $crud->fields(['name', 'email','password', 'publisher_id', 'campus_id', 'major_id']);
    $crud->requiredFields(['name', 'email']);
    $crud->columns(['name', 'email', 'password', 'updated_at']);

    $crud->callbackBeforeInsert(function ($s) {
      $s->data['password'] = bcrypt($s->data['password']);
      $s->data['created_at'] = now();
      $s->data['updated_at'] = now();
      return $s;
    });

    $crud->setRelation('publisher_id', 'publishers', 'name');
    $crud->setRelation('campus_id', 'campuses', 'name');
    $crud->setRelation('major_id', 'majors', 'name');

    $crud->displayAs([
      'publisher_id' => 'Publisher',
      'campus_id'  => 'Campus',
      'major_id' => 'Major'
    ]);
    $crud->callbackBeforeInsert(function ($s) {
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
      }else{
        unset($s->data['password']);
      }
      return $s;
    });

    $crud->callbackEditForm(function ($data) {
      $data['password'] = '';
      return $data;
    });

    $output = $crud->render();

    return $this->_showOutput($output, $title);
  }

  public function roles()
  {
    $this->authorize('manage users');
    $title = "Roles";
    $crud = $this->_getGroceryCrudEnterprise();

    $crud->setTable('model_has_roles');
    $crud->setSubject('Role', 'Roles');

    $crud->fields(['model_id', 'role_id']);
    $crud->columns(['model_id', 'role_id']);
    $crud->requiredFields(['role_id', 'model_id']);
    $crud->displayAs([
      'model_id' => 'Name',
      'role_id' => 'Role'
    ]);

    $crud->setRelation('role_id', 'roles', 'name');
    $crud->setRelation('model_id', 'users', 'name');

    $crud->callbackBeforeInsert(function ($s) {
      $s->data['model_type'] = 'App\Models\User';
      return $s;
    });

    $output = $crud->render();

    return $this->_showOutput($output, $title);
  }

  public function campuses()
  {
    $this->authorize('manage users');
    $title = "Campuses";
    $crud = $this->_getGroceryCrudEnterprise();

    $crud->setTable('campuses');
    $crud->setSubject('Campus', 'Campuses');
    $crud->where("campuses.deleted_at is null");

    $crud->fields(['name', 'address', 'email', 'phone']);
    $crud->columns(['name', 'address', 'email', 'phone']);
    $crud->requiredFields(['name', 'address', 'email', 'phone']);

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

    return $this->_showOutput($output, $title);
  }

  public function majors()
  {
    $this->authorize('manage users');
    $title = "Majors";
    $crud = $this->_getGroceryCrudEnterprise();

    $crud->setTable('majors');
    $crud->setSubject('Majors', 'Majors');
    $crud->where("majors.deleted_at is null");

    $crud->fields(['code', 'name']);
    $crud->columns(['code', 'name']);
    $crud->requiredFields(['code', 'name']);

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

    return $this->_showOutput($output, $title);
  }

  public function publisher()
  {
    $this->authorize('manage users');
    $title = "Publisher";
    $crud = $this->_getGroceryCrudEnterprise();

    $crud->setTable('publishers');
    $crud->setSubject('Publisher', 'Publisher');
    $crud->where("publishers.deleted_at is null");

    $crud->fields(['code', 'name', 'address', 'email', 'phone']);
    $crud->columns(['code','name', 'address', 'email', 'phone']);
    $crud->requiredFields(['code','name', 'address', 'email', 'phone']);

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

    return $this->_showOutput($output, $title);
  }
  }

