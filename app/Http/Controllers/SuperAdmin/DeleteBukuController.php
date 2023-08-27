<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\ProcurementBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteBukuController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $request->validate([
                'book'      => 'required|exists:books,id',
            ]);
            DB::beginTransaction();
            $book = Book::find($request->book);

            // set is_chosen ke false di procurement_books
            ProcurementBook::where([
                'procurement_id' => $book->procurement_id,
                'isbn' => $book->isbn,
            ])->update([
                'is_chosen' => false
            ]);

            $book->delete();
            DB::commit();
            return response()->json([
                'message' => 'success'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning('Error delete buku - Super Admin', [
                'data' => $request->all(),
                'book' => $book,
                'error' => $th->getMessage()
            ]);
            return response(status: 400)->json([
                'message' => 'success'
            ], 400);
        }
    }
}
