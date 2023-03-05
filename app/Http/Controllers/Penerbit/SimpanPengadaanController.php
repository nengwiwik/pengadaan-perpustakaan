<?php

namespace App\Http\Controllers\Penerbit;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
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
    public function __invoke(Invoice $invoice)
    {
        // otorisasi
        if ($invoice->publisher_id != Auth::user()->publisher_id) {
            return abort(404);
        }

        $invoice->invoice_date = now();
        $invoice->status = Invoice::STATUS_BARU;
        $invoice->save();

        PenerbitRepository::newProcurement($invoice);

        return to_route('penerbit.invoices');
    }
}
