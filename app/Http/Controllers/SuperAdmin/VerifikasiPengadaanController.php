<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Repositories\PenerbitRepository;
use Illuminate\Http\Request;

class VerifikasiPengadaanController extends Controller
{
    public function __invoke($id)
    {
        $data = Invoice::find(decrypt($id));
        PenerbitRepository::sendVerified($data);
        $data->verified_date = now();
        $data->status = Invoice::STATUS_SELESAI;
        $data->save();
        return redirect()->route('procurements.archived');
    }
}
