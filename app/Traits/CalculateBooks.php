<?php

namespace App\Traits;

use App\Models\Book;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

trait CalculateBooks
{
    public function calculateBooks(Invoice $invoice)
    {
        $data = Book::select(DB::raw('count(id) as total_books'))->whereNull('eksemplar')->whereBelongsTo($invoice)->first();

        $invoice->total_books = $data->total_books;
        $invoice->save();
    }

    public function calculatePrice(Invoice $invoice)
    {
        $data = Book::select(DB::raw('sum(price*eksemplar)*1 as total_price, sum(eksemplar)*1 as total_items, count(id) as total_books'))->whereNotNull('eksemplar')->whereBelongsTo($invoice)->first();

        $invoice->total_books = $data->total_books;
        $invoice->total_items = $data->total_items;
        $invoice->total_price = $data->total_price;
        $invoice->save();
    }
}
