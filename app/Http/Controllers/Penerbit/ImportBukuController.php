<?php

namespace App\Http\Controllers\Penerbit;

use App\Http\Controllers\Controller;
use App\Imports\BooksImport;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ImportBukuController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Invoice $invoice)
    {
        $validator = Validator::make($request->all(), [
            'upload' => 'required|file|mimes:xlsx'
        ]);

        if ($validator->fails()) {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }

        $path = $request->file('upload')->store('import-books');
        info($path);

        try {
            DB::beginTransaction();
            Excel::import(new BooksImport($invoice), $path, 'public');
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return back()->with('errors', $ex->getMessage())->withInput();
        }

        return back()->with('success', 'Data berhasil diimport');

    }
}
