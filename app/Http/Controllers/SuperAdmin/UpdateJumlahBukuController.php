<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateJumlahBukuController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $request->validate([
                'book'      => 'required|exists:books,id',
                'eksemplar' => 'required|numeric|min:0'
            ]);
            DB::beginTransaction();
            $book = Book::find($request->book);
            $book->eksemplar = $request->eksemplar;
            $book->save();
            DB::commit();
            return response()->json([
                'message' => 'success'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning('Error update jumlah buku - Super Admin', [
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
