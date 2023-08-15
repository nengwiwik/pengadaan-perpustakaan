<?php

namespace App\Http\Controllers\Penerbit;

use App\Http\Controllers\Controller;
use App\Imports\BooksImport;
use App\Models\Procurement;
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
    public function __invoke(Request $request, Procurement $procurement)
    {
        $validator = Validator::make($request->all(), [
            'jurusan' => 'required|numeric|exists:majors,id',
            'upload' => 'required|file|mimes:xlsx'
        ]);

        if ($validator->fails()) {
            return back()->with('errors', $validator->messages()->all()[0])->withInput();
        }

        $path = $request->file('upload')->store('import-books');
        info($path);

        try {
            DB::beginTransaction();
            Excel::import(new BooksImport($procurement, $request->jurusan), $path, 'public');
            DB::commit();
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            DB::rollBack();

            if (count($failures) > 0) {
                $row = $failures[0]->row(); // row that went wrong
                $column = $failures[0]->attribute(); // either heading key (if using heading row concern) or column index
                $error = $failures[0]->errors(); // Actual error messages from Laravel validator
                // $value = $failures[0]->values(); // The values of the row that has failed.

                return back()->with('errors', "Terjadi kesalahan pada Baris $row, Kolom $column, dengan pesan $error[0]")->withInput();
            }
        } catch (\Exception $ex) {
            DB::rollBack();
            return back()->with('errors', $ex->getMessage())->withInput();
        }

        return back()->with('success', 'Data berhasil diimport');

    }
}
