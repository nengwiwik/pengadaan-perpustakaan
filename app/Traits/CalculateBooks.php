<?php

namespace App\Traits;

use App\Models\ProcurementBook;
use App\Models\Procurement;
use Illuminate\Support\Facades\DB;

trait CalculateBooks
{
    public function calculateBooks(Procurement $procurement)
    {
        $data = ProcurementBook::select('isbn')->groupBy('isbn')->whereNull('eksemplar')->whereBelongsTo($procurement)->get()->count();

        $procurement->total_books = $data;
        $procurement->save();
    }

    public function calculatePrice(Procurement $procurement)
    {
        $data = ProcurementBook::select(DB::raw('sum(price*eksemplar)*1 as total_price, sum(eksemplar)*1 as total_items, count(id) as total_books'))->whereNotNull('eksemplar')->whereBelongsTo($procurement)->first();

        $procurement->total_books = $data->total_books;
        $procurement->total_items = $data->total_items;
        $procurement->total_price = $data->total_price;
        $procurement->save();
    }
}
