<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Procurement;
use App\Repositories\PenerbitRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TerimaPengadaanController extends Controller
{
    public function __invoke($id)
    {
        DB::beginTransaction();
        try {
            $data = Procurement::find(decrypt($id));
            PenerbitRepository::sendEmails($data);
            $data->approved_at = now();
            $data->cancelled_date = null;
            $data->status = Procurement::STATUS_AKTIF;
            $data->save();
            DB::commit();
            return redirect()->route('procurements.active');
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(500, "Ada masalah pada proses penerimaan pengadaan. Hubungi Web Administrator segera!");
        }
    }
}
