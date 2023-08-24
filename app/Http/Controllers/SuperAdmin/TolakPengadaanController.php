<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Procurement;
use App\Repositories\AdminRepository;
use App\Repositories\PenerbitRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TolakPengadaanController extends Controller
{
    public function __invoke($id)
    {
        try {
            DB::beginTransaction();
            $data = Procurement::find(decrypt($id));
            $data->approved_at = null;
            $data->cancelled_date = now();
            $data->status = Procurement::STATUS_DITOLAK;
            $data->save();
            DB::commit();
            AdminRepository::sendRejected($data);
            return redirect()->route('procurements.archived');
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
