<?php

namespace App\Http\Controllers\Penerbit;

use App\Http\Controllers\GroceryCrudController;
use App\Models\ProcurementBook;
use App\Models\Procurement;
use App\Models\Major;
use App\Traits\CalculateBooks;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengadaanBaruDetailController extends GroceryCrudController
{
    use CalculateBooks;

    public function __invoke(Procurement $procurement)
    {
        // otorisasi
        if ($procurement->publisher_id != Auth::user()->publisher_id) {
            return abort(403);
        }

        $title = "Data Buku | Nomor Pengadaan " . $procurement->code;
        $table = 'procurement_books';
        $singular = 'Buku';
        $plural = 'Data Buku';
        $crud = $this->_getGroceryCrudEnterprise();

        $crud->setTable($table);
        $crud->setSubject($singular, $plural);
        $crud->where([
            $table . '.procurement_id = ?' => $procurement->getKey(),
            $table . '.deleted_at is null',
        ]);

        $crud->setRead();
        $crud->columns(['major_id', 'cover', 'title', 'isbn', 'author_name', 'published_year', 'price', 'suplemen']);
        $crud->fields(['major_id', 'title', 'isbn', 'author_name', 'published_year', 'price', 'cover', 'summary', 'suplemen']);
        $crud->requiredFields(['major_id', 'title', 'isbn', 'author_name', 'published_year', 'price']);
        // $crud->setRelation('major_id', 'majors', 'name');
        $crud->fieldType('price', 'numeric');
        $crud->setTexteditor(['summary']);
        $crud->setFieldUpload('cover', 'storage', asset('storage'));
        $crud->callbackColumn('cover', function ($value, $row) {
            $data = ProcurementBook::find($row->id);
            return "<img src='" . $data->cover . "' height='150'>";
        });
        $crud->callbackReadField('cover', function ($fieldValue, $primaryKeyValue) {
            $data = ProcurementBook::find($primaryKeyValue);
            return "<img src='" . $data->cover . "' height='150'>";
        });
        $crud->fieldType('major_id', 'multiselect_searchable', Major::get()->pluck('name'));
        $crud->callbackReadField('major_id', function ($fieldValue, $primaryKeyValue) {
            $last_major = array_key_last($fieldValue);
            $res = "";
            $data_majors = Major::all();
            foreach ($data_majors as $key => $dmajor) {
                foreach ($fieldValue as $k => $major) {
                    if ($key == $major) {
                        $res .= $dmajor->name;
                        if ($k != $last_major) $res .= ", ";
                    }
                }
            }
            return $res;
        });
        $crud->displayAs([
            'major_id' => 'Jurusan',
            'isbn' => 'ISBN',
            'suplemen' => 'Suplemen',
            'author_name' => 'Penulis',
            'published_year' => 'Tahun Terbit',
            'price' => 'Harga',
            'title' => 'Judul Buku',
            'summary' => 'Ringkasan',
        ]);
        $crud->callbackBeforeInsert(function ($s) use ($procurement) {
            // info($s->data);
            // cek buku sudah pernah dibeli atau belum
            // gagalkan jika sudah pernah
            $cek = ProcurementBook::query()
                ->where('isbn', $s->data['isbn'])
                ->whereHas('procurement', function (Builder $query) {
                    $query->WhereNotIn('status', [Procurement::STATUS_DITOLAK]);
                    $query->where('publisher_id', auth()->user()->publisher_id);
                })
                ->first();
            if ($cek) {
                $errorMessage = new \GroceryCrud\Core\Error\ErrorMessage();
                return $errorMessage->setMessage('Tidak bisa menawarkan buku yang sama.');
            }
            $s->data['procurement_id'] = $procurement->getKey();
            $s->data['created_at'] = now();
            $s->data['updated_at'] = now();
            return $s;
        });
        $crud->callbackAfterInsert(function ($s) {
            $inv = ProcurementBook::find($s->insertId);
            $this->calculateBooks($inv->procurement);

            return $s;
        });
        $crud->callbackBeforeUpdate(function ($s) {
            // cek buku sudah pernah dibeli atau belum
            // gagalkan jika sudah pernah
            $cek = ProcurementBook::query()
                ->where('isbn', $s->data['isbn'])
                ->whereHas('procurement', function (Builder $query) {
                    $query->WhereNotIn('status', [Procurement::STATUS_DITOLAK]);
                    $query->where('publisher_id', auth()->user()->publisher_id);
                })
                ->first();
            if ($cek) {
                $errorMessage = new \GroceryCrud\Core\Error\ErrorMessage();
                return $errorMessage->setMessage('Tidak bisa menawarkan buku yang sama.');
            }

            return $s;
        });
        $crud->callbackAfterUpdate(function ($s) {
            $inv = ProcurementBook::find($s->primaryKeyValue);
            $this->calculateBooks($inv->procurement);

            return $s;
        });
        $crud->callbackDelete(function ($s) {
            $data = ProcurementBook::find($s->primaryKeyValue);
            $procurement = $data->procurement;

            if (!$data) {
                $errorMessage = new \GroceryCrud\Core\Error\ErrorMessage();
                return $errorMessage->setMessage('Data not found');
            }

            $data->save();
            $data->delete();
            $this->calculateBooks($procurement);
            return $s;
        });
        $crud->callbackBeforeUpdate(function ($s) {
            $s->data['updated_at'] = now();
            return $s;
        });

        $output = $crud->render();

        return $this->_showOutput($output, $title, 'penerbit.invoice.buku');
    }
}
