<?php

namespace App\Observers;

use App\Models\Book;

class BookObserver
{
    public function recalculate(Book $book): void
    {
        $total_items = 0;
        $total_price = 0;
        $procurement = $book->procurement;
        foreach ($procurement->books as $book) {
            $total_items += $book->eksemplar;
            $total_price += $book->eksemplar * $book->price;
        }

        $procurement->total_items = $total_items;
        $procurement->total_price = $total_price;
        $procurement->save();
    }

    /**
     * Handle the Book "created" event.
     */
    public function created(Book $book): void
    {
        $this->recalculate($book);
    }

    /**
     * Handle the Book "updated" event.
     */
    public function updated(Book $book): void
    {
        $this->recalculate($book);
    }

    /**
     * Handle the Book "deleted" event.
     */
    public function deleted(Book $book): void
    {
        $this->recalculate($book);
    }

    /**
     * Handle the Book "restored" event.
     */
    public function restored(Book $book): void
    {
        $this->recalculate($book);
    }

    /**
     * Handle the Book "force deleted" event.
     */
    public function forceDeleted(Book $book): void
    {
        $this->recalculate($book);
    }
}
