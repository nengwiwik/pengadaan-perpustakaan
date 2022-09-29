<?php

namespace App\Http\Controllers\GroceryCrud;

use App\Http\Controllers\Controller;
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

    $crud->fields(['name', 'email', 'password']);
    $crud->requiredFields(['name', 'email']);
    $crud->columns(['name', 'email', 'password', 'updated_at']);

    $crud->callbackBeforeInsert(function ($s) {
      $s->data['password'] = bcrypt($s->data['password']);
      $s->data['created_at'] = now();
      $s->data['updated_at'] = now();
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
}
