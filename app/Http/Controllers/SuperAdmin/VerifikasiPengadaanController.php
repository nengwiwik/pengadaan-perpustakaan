<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Procurement;
use App\Repositories\AdminRepository;
use App\Repositories\PenerbitRepository;
use Illuminate\Http\Request;

class VerifikasiPengadaanController extends Controller
{
    public function __invoke($id)
    {
        $data = Procurement::find(decrypt($id));
        AdminRepository::sendVerified($data);
        $data->verified_date = now();
        $data->status = Procurement::STATUS_INVOICE;
        $data->save();
        return redirect()->route('procurements.archived');
    }
}
