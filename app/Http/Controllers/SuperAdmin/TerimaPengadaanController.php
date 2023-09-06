<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Procurement;
use App\Repositories\AdminRepository;
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
            if ($data->budget <= 0) {
                return redirect()->back()->with('error', 'Tidak bisa menerima sebelum menuliskan budget pengadaan.');
            }
            $data->approved_at = now();
            $data->cancelled_date = null;
            $data->status = Procurement::STATUS_AKTIF;
            $data->save();
            AdminRepository::sendEmails($data);
            DB::commit();
            return redirect()->route('procurements.active');
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(500, "Ada masalah pada proses penerimaan pengadaan. Hubungi Web Administrator segera!");
        }
    }
}
