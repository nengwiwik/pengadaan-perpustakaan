<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Campus;
use App\Models\Invoice;
use App\Models\Major;
use App\Models\Publisher;
use App\Models\User;
use App\Repositories\PenerbitRepository;
use App\Traits\CalculateBooks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends GroceryCrudController
{
    use CalculateBooks;

    public function prodi_users()
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

    public function publisher_users()
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

        $crud->callbackBeforeInsert(function ($s) {
            $s->data['password'] = bcrypt($s->data['password']);
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

    public function inactive_users()
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
        $title = "Kampus";
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable('campuses');
        $crud->setSubject('Kampus', 'Data Kampus');
        $crud->where("campuses.deleted_at is null");

        $crud->fields(['name', 'address', 'email', 'phone']);
        $crud->columns(['name', 'address', 'email', 'phone']);
        $crud->requiredFields(['name', 'address', 'email', 'phone']);

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

    public function majors()
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

    public function publisher()
    {
        $this->authorize('manage users');
        $title = "Penerbit";
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable('publishers');
        $crud->setSubject('Penerbit', 'Data Penerbit');
        $crud->where("publishers.deleted_at is null");

        $crud->fields(['code', 'name', 'address', 'email', 'phone']);
        $crud->columns(['code', 'name', 'address', 'email', 'phone']);
        $crud->requiredFields(['code', 'name', 'address', 'email', 'phone']);
        $crud->uniqueFields(['code']);

        $crud->displayAs([
            'code' => 'Kode',
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
            $data = Publisher::find($s->primaryKeyValue);
            $data->delete();
            return $s;
        });

        $output = $crud->render();

        return $this->_showOutput($output, $title, 'grocery', 'penerbit');
    }

    public function new_procurements()
    {
        $title = "Pengadaan Baru";
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable('invoices');
        $crud->setSubject('Pengadaan Baru', 'Data Pengadaan Baru');
        $crud->where([
            "invoices.deleted_at is null",
            "invoices.approved_at is null",
            "invoices.verified_date is null",
            "invoices.cancelled_date is null",
            "invoices.paid_date is null"
        ]);

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
        $crud->callbackColumn('code', function ($value, $row) {
            return "<a href='" . route('procurements.books', $row->id) . "'>" . $value . "</a>";
        });
        $crud->setActionButton('Mark as Approved', 'fa fa-check', function ($row) {
            return route('procurement.approve', encrypt($row->id));
        }, false);
        $crud->setActionButton('Mark as Rejected', 'fa fa-times', function ($row) {
            return route('procurement.reject', encrypt($row->id));
        }, false);
        // $crud->setActionButton('Mark as Verified', 'fa fa-save', function ($row) {
        //     return route('procurement.verify', encrypt($row->id));
        // }, false);
        $crud->displayAs([
            'code' => 'Kode',
            'created_at' => 'Status',
            'publisher_id' => 'Penerbit',
            'campus_id' => 'Kampus',
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

        return $this->_showOutput($output, $title, 'grocery', 'pengadaan');
    }

    public function books_procurements(Invoice $invoice)
    {
        $title = "Data Buku | ID Pengadaan " . $invoice->code;
        $table = 'books';
        $singular = 'Buku';
        $plural = 'Data Buku';
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable($table);
        $crud->setSubject($singular, $plural);
        $crud->where([
            $table . '.invoice_id = ?' => $invoice->getKey(),
            $table . '.deleted_at is null',
        ]);

        $crud->unsetOperations()->setEdit()->setRead();
        $crud->columns(['major_id', 'title', 'published_year', 'eksemplar', 'is_chosen', 'isbn', 'author_name', 'price', 'suplemen']);
        $crud->fields(['title', 'eksemplar']);
        $crud->readFields(['title', 'eksemplar', 'major_id', 'published_year', 'isbn', 'author_name', 'price', 'suplemen']);
        $crud->requiredFields(['title', 'eksemplar']);
        $crud->setRelation('major_id', 'majors', 'name');
        $crud->fieldType('price', 'numeric');
        $crud->fieldType('is_chosen', 'checkbox_boolean');
        $crud->displayAs([
            'major_id' => 'Jurusan',
            'isbn' => 'ISBN',
            'published_year' => 'Tahun Terbit',
            'author_name' => 'Nama Penulis',
            'suplemen' => 'Suplemen',
            'is_chosen' => 'Pilih Buku',
        ]);
        $crud->callbackBeforeUpdate(function ($s) {
            $book = Book::find($s->primaryKeyValue);
            $s->data['title'] = $book->title;

            return $s;
        });
        $crud->callbackAfterUpdate(function ($s) {
            $inv = Book::find($s->primaryKeyValue);

            if ($inv->eksemplar > 0) {
                $inv->is_chosen = 1;
                $this->calculatePrice($inv->invoice);
            } else {
                $inv->eksemplar = null;
                $inv->is_chosen = 0;
            }

            $inv->save();

            return $s;
        });

        $output = $crud->render();

        return $this->_showOutput($output, $title, 'superadmin.invoice.buku');
    }

    public function active_procurements()
    {
        $title = "Pengadaan Aktif";
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable('invoices');
        $crud->setSubject('Pengadaan Aktif', 'Pengadaan Aktif');
        $crud->where([
            "invoices.deleted_at is null",
            "invoices.approved_at is not null",
        ]);

        // $crud->fields(['code', 'name', 'address', 'email', 'phone']);
        // $crud->requiredFields(['code', 'name', 'address', 'email', 'phone']);
        $crud->columns(['code', 'publisher_id', 'campus_id', 'total_books', 'total_items', 'total_price']);
        $crud->setRelation('publisher_id', 'publishers', 'name');
        $crud->setRelation('campus_id', 'campuses', 'name');
        $crud->fields(['campus_note'])->setTexteditor(['campus_note']);
        $crud->unsetAdd()->unsetDelete()->setRead();
        $crud->setTexteditor(['campus_note', 'publisher_note']);
        $crud->readFields(['code', 'publisher_id', 'campus_id', 'invoice_date', 'approved_at', 'campus_note', 'publisher_note', 'total_books', 'total_items', 'total_price']);
        $crud->callbackColumn('code', function ($value, $row) {
            return "<a href='" . route('procurements.books', $row->id) . "'>" . $value . "</a>";
        });
        $crud->callbackReadField('total_books', function ($value, $row) {
            return number_format($value, 0, ',', '.');
        });
        $crud->callbackReadField('total_items', function ($value, $row) {
            return number_format($value, 0, ',', '.');
        });
        $crud->callbackReadField('total_price', function ($value, $row) {
            return "IDR " . number_format($value, 0, ',', '.');
        });
        $crud->callbackColumn('total_price', function ($value, $row) {
            return "IDR " . number_format($value, 0, ',', '.');
        });
        $crud->setActionButton('Mark as Verified', 'fa fa-save', function ($row) {
            return route('procurement.verify', encrypt($row->id));
        }, false);
        $crud->displayAs([
            'created_at' => 'Status',
            'publisher_id' => 'Penerbit',
            'campus_id' => 'Kampus',
            'total_books' => 'Jumlah Buku',
            'total_items' => 'Jumlah Barang',
            'total_price' => 'Total Harga',
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

        return $this->_showOutput($output, $title, 'grocery', 'pengadaan');
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

    public function procurement_verify($id)
    {
        $data = Invoice::find(decrypt($id));
        $data->verified_date = now();
        $data->save();
        return redirect()->route('procurements');
    }
}
