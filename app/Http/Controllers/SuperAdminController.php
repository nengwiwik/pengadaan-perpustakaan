<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\Invoice;
use App\Models\Major;
use App\Models\Publisher;
use App\Models\User;
use App\Repositories\PenerbitRepository;
use Illuminate\Http\Request;

class SuperAdminController extends GroceryCrudController
{
    public function prodi_users()
    {
        $this->authorize('manage users');
        $title = "Admin Prodi";
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable('users');
        $crud->setSubject('Admin', 'Administrators');

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
            'campus_id'  => 'Campus',
            'major_id' => 'Major'
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

    public function publisher_users()
    {
        $this->authorize('manage users');
        $title = "Admin Publishers";
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable('users');
        $crud->setSubject('Admin', 'Admin Publishers');

        $crud->fields(['name', 'email', 'password', 'publisher_id']);
        $crud->requiredFields(['name', 'email', 'password', 'publisher_id']);
        $crud->columns(['name', 'email', 'publisher_id', 'updated_at']);

        $crud->where([
            "publisher_id is not null",
        ]);

        $crud->callbackBeforeInsert(function ($s) {
            $s->data['password'] = bcrypt($s->data['password']);
            $s->data['created_at'] = now();
            $s->data['updated_at'] = now();
            return $s;
        });

        $crud->setRelation('publisher_id', 'publishers', 'name');

        $crud->displayAs([
            'publisher_id'  => 'Publisher Name',
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

    public function inactive_users()
    {
        $this->authorize('manage users');
        $title = "Inactive Users";
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable('users');
        $crud->setSubject('User', 'Inactive Users');

        $crud->unsetAdd();
        $crud->fields(['name', 'campus_id', 'major_id']);
        $crud->requiredFields(['name', 'campus_id', 'major_id']);
        $crud->columns(['name', 'email', 'updated_at']);

        $crud->where([
            "publisher_id is null",
            "campus_id is null",
            "email != ?" => env('ADMIN_EMAIL', ["admin@undira.ac.id", "admin@gmail.com"]),
        ]);

        $crud->callbackBeforeInsert(function ($s) {
            $s->data['created_at'] = now();
            $s->data['updated_at'] = now();
            return $s;
        });

        $crud->setRelation('major_id', 'majors', 'name');
        $crud->setRelation('campus_id', 'campuses', 'name');

        $crud->displayAs([
            'major_id'  => 'Major Name',
            'campus_id'  => 'Campus Name',
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
        $crud->columns(['code', 'name', 'address', 'email', 'phone']);
        $crud->requiredFields(['code', 'name', 'address', 'email', 'phone']);

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

    public function procurements()
    {
        $title = "Procurements";
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable('invoices');
        $crud->setSubject('Procurements', 'Procurements');
        $crud->where("invoices.deleted_at is null");

        // $crud->fields(['code', 'name', 'address', 'email', 'phone']);
        // $crud->requiredFields(['code', 'name', 'address', 'email', 'phone']);
        $crud->columns(['created_at', 'code', 'publisher_id', 'campus_id', 'cancelled_date', 'approved_at']);
        $crud->fieldTypeColumn('cancelled_date', 'invisible');
        $crud->fieldTypeColumn('approved_at', 'invisible');
        $crud->setRelation('publisher_id', 'publishers', 'name');
        $crud->setRelation('campus_id', 'campuses', 'name');
        $crud->fields(['campus_note'])->setTexteditor(['campus_note']);
        $crud->unsetAdd()->unsetDelete()->setRead()->unsetReadFields(['deleted_at', 'updated_at']);
        $crud->callbackColumn('created_at', function ($value, $row) {
            if (is_null($row->approved_at) && is_null($row->cancelled_date)) {
                return "Pending";
            }
            if (! is_null($row->approved_at)) {
                return "Approved";
            }
            if (! is_null($row->cancelled_date)) {
                return "Rejected";
            }
        });
        $crud->setActionButton('Mark as Approved', 'fa fa-check', function ($row) {
            $invoice = Invoice::find($row->id);
            return route('procurement.approve', encrypt($row->id));
        }, false);
        $crud->setActionButton('Mark as Rejected', 'fa fa-times', function ($row) {
            return route('procurement.reject', encrypt($row->id));
        }, false);
        $crud->setActionButton('Mark as Verified', 'fa fa-save', function ($row) {
            return route('procurement.verify', encrypt($row->id));
        }, false);
        $crud->displayAs([
            'created_at' => 'Status',
            'publisher_id' => 'Publisher',
            'campus_id' => 'Campus',
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
            $data = Invoice::find($s->primaryKeyValue);
            $data->delete();
            return $s;
        });

        $output = $crud->render();

        return $this->_showOutput($output, $title);
    }

    public function procurement_approve($id)
    {
        $data = Invoice::find(decrypt($id));
        PenerbitRepository::sendEmails($data);
        $data->approved_at = now();
        $data->cancelled_date = null;
        $data->save();
        return redirect()->route('procurements');
    }

    public function procurement_reject($id)
    {
        $data = Invoice::find(decrypt($id));
        $data->approved_at = null;
        $data->cancelled_date = now();
        $data->save();
        return redirect()->route('procurements');
    }
}
