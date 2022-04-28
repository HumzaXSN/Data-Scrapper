<?php

namespace App\Imports;

use App\Models\Contact;
use App\Models\Industry;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ContactsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure, WithUpserts, WithChunkReading, ShouldQueue
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
        $industy = Industry::where('name', $row['industry'])->first();
        $getContact = Contact::where([['list_id', 1], ['email', $row['email']]])->get();
        if(count($getContact) > 0) {
            dd($row);
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
                'linkedIn_profile' => $row['linkedin_profile'] ?? NULL,
                'source' => $this->source,
                'list_id' => 1
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
                'linkedIn_profile' => $row['linkedin_profile'] ?? NULL,
                'source' => $this->source,
                'list_id' => $this->listId
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
