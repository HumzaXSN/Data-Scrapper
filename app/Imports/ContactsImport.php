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
    protected $source, $listId;
    public function  __construct($sourceId, $listId)
    {
        $this->listId = $listId;
        $this->sourceId = $sourceId;
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
        $industy = Industry::firstOrCreate(['name' => $row['industry']]);
        $getContact = Contact::where([['lists_id', 1], ['email', $row['email']]])->get();
        if(count($getContact) > 0) {
            return new Contact([
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'] ?? NULL,
                'title' => $row['title'] ?? NULL,
                'company' => $row['company'] ?? NULL,
                'email' => $row['email'],
                'unsub_link' => base64_encode($row['email']),
                'phone' => $row['phone'] ?? NULL,
                'country' => $row['country'] ?? NULL,
                'city' => $row['city'] ?? NULL,
                'state' => $row['state'] ?? NULL,
                'industry_id' => $industy->id ?? 1,
                'business_platform' => $row['business_platform'] ?? NULL,
                'linkedIn_profile' => $row['linkedin_profile'] ?? NULL,
                'source_id' => $this->sourceId,
                'lists_id' => $this->listId,
            ]);
        } else {
            return new Contact([
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'] ?? NULL,
                'title' => $row['title'] ?? NULL,
                'company' => $row['company'] ?? NULL,
                'email' => $row['email'],
                'unsub_link' => base64_encode($row['email']),
                'phone' => $row['phone'] ?? NULL,
                'country' => $row['country'] ?? NULL,
                'city' => $row['city'] ?? NULL,
                'state' => $row['state'] ?? NULL,
                'industry_id' => $industy->id ?? 1,
                'business_platform' => $row['business_platform'] ?? NULL,
                'linkedIn_profile' => $row['linkedin_profile'] ?? NULL,
                'source_id' => $this->sourceId,
                'lists_id' => $this->listId
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
            '*.email' => ['required', 'email'],
            '*.first_name' => ['required'],
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
