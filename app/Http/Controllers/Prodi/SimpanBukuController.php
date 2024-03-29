<?php

namespace App\Http\Controllers\Prodi;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Procurement;
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
        info($request->all());
        try {
            DB::beginTransaction();
            $request->validate([
                'id' => 'required|exists:procurement_books,id',
                'checked' => 'required|boolean'
            ]);
            $book = ProcurementBook::find($request->id);

            // cek statusnya. hanya boleh jika status pengadaan AKTIF
            if ($book->procurement->status != Procurement::STATUS_AKTIF) {
                throw new \Exception('Tidak bisa memilih buku: Pengadaan sudah tidak aktif');
            }

            $book->is_chosen = $request->checked;
            $book->save();

            $this->addToCart($book);
            $book->procurement->refresh();
            if ($book->procurement->total_price > $book->procurement->budget) {
                throw new \Exception('Ups, sudah melebihi budget.');
            }
            DB::commit();

            $result = [
                'total_books' => $book->procurement->total_books . ' buku',
                // 'total_items' => $book->procurement->total_items . ' eksemplar',
                'total_price' => 'Rp ' . number_format($book->procurement->total_price, 0, ',', '.'),
            ];

            return response()->json($result);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response($th->getMessage(), 400);
        }
    }

    function addToCart(ProcurementBook $procurementBook): void
    {
        // cek ISBN di tabel procurement_books, jika ada salah satu is_chosen=1,
        $cek = ProcurementBook::firstWhere([
            'isbn' => $procurementBook->isbn,
            'is_chosen' => true
        ]);

        // jika ada, tambahkan ke tabel books
        if ($cek) {
            Book::updateOrCreate(
                [
                    'procurement_id' => $procurementBook->procurement_id,
                    'campus_id' => $procurementBook->procurement->campus_id,
                    'isbn' => $procurementBook->isbn
                ],
                [
                    'title' => $procurementBook->title,
                    'author_name' => $procurementBook->author_name,
                    'published_year' => $procurementBook->published_year,
                    'price' => $procurementBook->price,
                    'suplemen' => $procurementBook->suplemen,
                ]
            );
        }

        // jika tidak ada, hapus dari tabel books
        if (!$cek) {
            $books = Book::where([
                'procurement_id' => $procurementBook->procurement_id,
                'isbn' => $procurementBook->isbn
            ])->get();
            foreach($books as $book) {
                $book->delete();
            }
        }

        // kalkulasi ulang total_items dan total_price pada tabel procurements
        // $this->recalculateTotalItems($procurementBook->procurement);
    }

    function recalculateTotalItems(Procurement $procurement): void
    {
        $total_items = 0;
        $total_price = 0;
        foreach ($procurement->books as $book) {
            $total_items += $book->eksemplar;
            $total_price += $book->eksemplar * $book->price;
        }

        $procurement->total_items = $total_items;
        $procurement->total_price = $total_price;
        $procurement->save();
    }
}
