<?php

namespace App\Imports;

use App\Models\ProcurementBook;
use App\Models\Procurement;
use App\Traits\CalculateBooks;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BooksImport implements
    ToCollection,
    WithChunkReading,
    ShouldQueue,
    SkipsEmptyRows,
    WithHeadingRow,
    WithValidation
{
    use CalculateBooks;

    public function __construct(
        public Procurement $procurement,
        public $major_id
    ) {
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // skip jika ada buku dengan ISBN yang sama
            $cek = ProcurementBook::query()
                ->where('isbn', $row['isbn'])
                ->whereHas('procurement', function (Builder $query) {
                    $query->WhereNotIn('status', [Procurement::STATUS_DITOLAK]);
                    $query->where('publisher_id', auth()->user()->publisher_id);
                    $query->where('major_id', $this->major_id);
                    $query->where('campus_id', $this->procurement->campus_id);
                })
                ->first();
            if ($cek) continue;

            // input buku
            ProcurementBook::updateOrCreate(
                [
                    'procurement_id' => $this->procurement->getKey(),
                    'isbn' => $row['isbn'],
                    'major_id' => $this->major_id,
                ],
                [
                    'title' => $row['title'],
                    'author_name' => $row['author_name'],
                    'published_year' => $row['published_year'],
                    'price' => $row['price'],
                    'suplemen' => $row['suplement'],
                    'summary' => $row['summary'],
                ]
            );
        }

        // hitung ulang jumlah buku (total_books) pada tabel procurements
        $this->calculateBooks($this->procurement);
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:1000',
            'isbn' => 'required|string|max:20',
            'author_name' => 'required|string|max:1000',
            'published_year' => 'required|numeric',
            'price' => 'required|numeric|max_digits:9',
            'suplement' => 'nullable|string|max:20',
            'summary'   => 'nullable|string'
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
