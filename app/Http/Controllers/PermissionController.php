<?php

namespace App\Http\Controllers;

class PermissionController extends GroceryCrudController
{
  public function permissions()
  {
    $this->authorize('manage permissions');
    $title = "Permissions";
    $crud = $this->_getGroceryCrudEnterprise();

    $crud->setTable('permissions');
    $crud->setSubject('Permission', 'Permissions');

    $crud->fields(['name']);
    $crud->requiredFields(['name']);
    $crud->columns(['name']);
    $crud->callbackBeforeInsert(function ($s) {
      $s->data['guard_name'] = 'web';
      $s->data['created_at'] = now();
      $s->data['updated_at'] = now();
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
