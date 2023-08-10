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
    ShouldQueue
// WithValidation
{
    use CalculateBooks;

    public function __construct(public Procurement $procurement, public $major_id)
    {
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            if ($key == 0) {
                continue;
            }
            $inv = ProcurementBook::updateOrCreate(
                [
                    'procurement_id' => $this->procurement->getKey(),
                    'isbn' => $row[2],
                    'major_id' => $this->major_id,
                ],
                [
                    'title' => $row[1],
                    'author_name' => $row[3],
                    'published_year' => $row[4],
                    'price' => $row[5],
                    'suplemen' => $row[6],
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
            '*.0' => 'required|exists:procurements,code',
            '*.1' => 'required|exists:majors,name',
            '*.2' => 'required|string|max:255',
            '*.3' => 'required|alpha_num|max:255',
            '*.4' => 'required|string|max:255',
            '*.5' => 'required|integer',
            '*.6' => 'required|integer',
            '*.7' => 'required|string|max:255',
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
