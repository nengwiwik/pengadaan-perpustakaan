<?php

namespace App\Http\Controllers\Prodi;

use App\Http\Controllers\Controller;
use App\Models\ProcurementBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SimpanBukuController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:procurement_books,id',
                'checked' => 'required|boolean'
            ]);
            DB::beginTransaction();
            $book = ProcurementBook::find($request->id);
            $book->is_chosen = $request->checked;
            $book->save();
            DB::commit();
            return response()->json($request->all());
        } catch (\Throwable $th) {
            DB::rollBack();
            return response($th->getMessage(), 500);
        }
    }
}
