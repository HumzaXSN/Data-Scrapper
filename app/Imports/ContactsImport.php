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
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ContactsImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading, SkipsOnError, SkipsOnFailure
{
    private $success_rows = 0;
    use Importable, SkipsFailures, SkipsErrors;
    protected $source, $listId;
    public function  __construct($source, $listId)
    {
        $this->listId = $listId;
        $this->source= $source;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $industy = Industry::where('name', $row['industry'])->first();
        ++$this->success_rows;
        if($this->listId != null) {
            $getContact = Contact::create([
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'title' => $row['title'] ?? NULL,
                'company' => $row['company'] ?? NULL,
                'email' => $row['email'],
                'phone' => $row['phone'] ?? NULL,
                'country' => $row['country'] ?? NULL,
                'city' => $row['city'] ?? NULL,
                'state' => $row['state'] ?? NULL,
                'industry_id' => $industy->id ?? NULL,
                'linkedIn_profile' => $row['linkedin_profile'] ?? NULL,
                'source' => $this->source,
            ]);
            $getContact->lists()->attach($this->listId);
        } else {
            return new Contact([
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'title' => $row['title'] ?? NULL,
                'company' => $row['company'] ?? NULL,
                'email' => $row['email'],
                'phone' => $row['phone'] ?? NULL,
                'country' => $row['country'] ?? NULL,
                'city' => $row['city'] ?? NULL,
                'state' => $row['state'] ?? NULL,
                'industry_id' => $industy->id ?? NULL,
                'linkedIn_profile' => $row['linkedin_profile'] ?? NULL,
                'source' => $this->source,
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
            '*.email' => ['required', 'unique:contacts,email'],
            '*.first_name' => ['required'],
        ];
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
