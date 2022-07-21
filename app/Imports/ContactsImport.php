<?php

namespace App\Imports;

use App\Models\Contact;
use App\Models\Industry;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;

class ContactsImport implements ToModel, WithHeadingRow, SkipsOnError, SkipsOnFailure, WithChunkReading, WithValidation, WithUpserts
{
    private $success_rows = 0;
    use Importable, SkipsFailures, SkipsErrors;
    protected $sourceId, $listId, $excelcolumns0, $excelcolumns1, $excelcolumns2, $excelcolumns3, $excelcolumns4, $excelcolumns5, $excelcolumns6, $excelcolumns7, $excelcolumns8, $excelcolumns9, $excelcolumns10;
    public function  __construct($sourceId, $listId, $excelcolumns0, $excelcolumns1, $excelcolumns2, $excelcolumns3, $excelcolumns4, $excelcolumns5, $excelcolumns6, $excelcolumns7, $excelcolumns8, $excelcolumns9, $excelcolumns10)
    {
        $this->listId = $listId;
        $this->sourceId = $sourceId;
        $this->columns[0] = $excelcolumns0;
        $this->columns[1] = $excelcolumns1;
        $this->columns[2] = $excelcolumns2;
        $this->columns[3] = $excelcolumns3;
        $this->columns[4] = $excelcolumns4;
        $this->columns[5] = $excelcolumns5;
        $this->columns[6] = $excelcolumns6;
        $this->columns[7] = $excelcolumns7;
        $this->columns[8] = $excelcolumns8;
        $this->columns[9] = $excelcolumns9;
        $this->columns[10] = $excelcolumns10;
    }

    /**
     * @return string|array
     */
    public function uniqueBy()
    {
        return 'email';
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        ++$this->success_rows;
        if ($this->columns[9] != 'NULL') {
            $industy = Industry::firstOrCreate(['name' => $row[strtolower($this->columns[9])]]);
        }
        $getContact = Contact::where([['lists_id', 1], ['email', $row[strtolower($this->columns[4])]]])->get();
        if (count($getContact) > 0) {
            return new Contact([
                'first_name' => $row[strtolower($this->columns[0])],
                'last_name' => $row[strtolower($this->columns[1])] ?? NULL,
                'title' => $row[strtolower($this->columns[2])] ?? NULL,
                'company' => $row[strtolower($this->columns[3])] ?? NULL,
                'email' => $row[strtolower($this->columns[4])],
                'unsub_link' => base64_encode(strtolower($this->columns[4])),
                'phone' => $row[strtolower($this->columns[5])] ?? NULL,
                'country' => $row[strtolower($this->columns[6])] ?? NULL,
                'state' => $row[strtolower($this->columns[7])] ?? NULL,
                'city' => $row[strtolower($this->columns[8])] ?? NULL,
                'industry_id' => $industy->id ?? 1,
                'linkedIn_profile' => $row[strtolower($this->columns[10])] ?? NULL,
                'source_id' => $this->sourceId,
                'lists_id' => $this->listId,
            ]);
        } else {
            return new Contact([
                'first_name' => $row[strtolower($this->columns[0])],
                'last_name' => $row[strtolower($this->columns[1])] ?? NULL,
                'title' => $row[strtolower($this->columns[2])] ?? NULL,
                'company' => $row[strtolower($this->columns[3])] ?? NULL,
                'email' => $row[strtolower($this->columns[4])],
                'unsub_link' => base64_encode(strtolower($this->columns[4])),
                'phone' => $row[strtolower($this->columns[5])] ?? NULL,
                'country' => $row[strtolower($this->columns[6])] ?? NULL,
                'state' => $row[strtolower($this->columns[7])] ?? NULL,
                'city' => $row[strtolower($this->columns[8])] ?? NULL,
                'industry_id' => $industy->id ?? 1,
                'linkedIn_profile' => $row[strtolower($this->columns[10])] ?? NULL,
                'source_id' => $this->sourceId,
                'lists_id' => $this->listId,
            ]);
        }
    }

    public function getRowCount(): int
    {
        return $this->success_rows;
    }

    public function rules(): array
    {
        return [
            '*.' . strtolower($this->columns[4]) . '' => ['required', 'email'],
            '*.' . strtolower($this->columns[0]) . '' => ['required'],
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
