<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Repositories\PenerbitRepository;
use Illuminate\Http\Request;

class TerimaPengadaanController extends Controller
{
    public function __invoke($id)
    {
        $data = Invoice::find(decrypt($id));
        PenerbitRepository::sendEmails($data);
        $data->approved_at = now();
        $data->cancelled_date = null;
        $data->status = Invoice::STATUS_AKTIF;
        $data->save();
        return redirect()->route('procurements.active');
    }
}
