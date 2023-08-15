<?php

namespace App\Imports;

use App\Models\ProcurementBook;
use App\Models\Procurement;
use App\Models\Major;
use App\Traits\CalculateBooks;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
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
            $inv = ProcurementBook::updateOrCreate(
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
            $this->calculateBooks($inv->procurement);
        }
    }

    public function getMajor($major)
    {
        $data =  Major::firstWhere('name', $major);
        return $data->id;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:100',
            'isbn' => 'required|string|max:20',
            'author_name' => 'required|string|max:100',
            'published_year' => 'required|numeric',
            'price' => 'required|numeric',
            'suplement' => 'nullable|string|max:20',
            'summary'   => 'nullable|string'
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
