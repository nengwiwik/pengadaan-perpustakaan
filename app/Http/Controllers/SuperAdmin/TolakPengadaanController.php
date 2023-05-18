<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Procurement;
use App\Repositories\PenerbitRepository;
use Illuminate\Http\Request;

class TolakPengadaanController extends Controller
{
    public function __invoke($id)
    {
        $data = Procurement::find(decrypt($id));
        PenerbitRepository::sendRejected($data);
        $data->approved_at = null;
        $data->cancelled_date = now();
        $data->status = Procurement::STATUS_DITOLAK;
        $data->save();
        return redirect()->route('procurements.archived');
    }
}
