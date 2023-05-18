<?php

namespace App\Http\Controllers\Penerbit;

use App\Http\Controllers\Controller;
use App\Models\Procurement;
use App\Repositories\PenerbitRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SimpanPengadaanController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Procurement $procurement)
    {
        // otorisasi
        if ($procurement->publisher_id != Auth::user()->publisher_id) {
            return abort(404);
        }

        $procurement->invoice_date = now();
        $procurement->status = Procurement::STATUS_BARU;
        $procurement->save();

        PenerbitRepository::newProcurement($procurement);

        return to_route('penerbit.invoices');
    }
}
