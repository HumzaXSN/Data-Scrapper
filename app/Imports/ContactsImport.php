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
        $getList = $this->listId;
        ++$this->success_rows;
        $industy = Industry::where('name', $row['industry'])->first();
        $getContact = Contact::where('email', $row['email'])->first();
        $getBlock = Contact::where('email', $row['email'])->whereHas('lists', function ($query) {
            $query->where('list_id', 1);
        })->first();
        if (isset($getBlock)) {
            return null;
        } else {
            if($getList != 1) {
                if(isset($getContact)) {
                    $getContact->lists()->syncWithoutDetaching($getList);
                } else {
                    $getContact = Contact::create([
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
                    ]);
                    $getContact->lists()->syncWithoutDetaching($getList);
                }
            }
            elseif($getList == 1) {
                if (isset($getContact)) {
                    $getContact->lists()->sync($getList);
                } else {
                    $getContact = Contact::create([
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
                        'industry_id' => $industy->id ?? NULL,
                        'linkedIn_profile' => $row['linkedin_profile'] ?? NULL,
                        'source' => $this->source,
                    ]);
                    $getContact->lists()->sync($getList);
                }
            }
            else {
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
                    'industry_id' => $industy->id ?? NULL,
                    'linkedIn_profile' => $row['linkedin_profile'] ?? NULL,
                    'source' => $this->source,
                ]);
            }
        }
    }

    public function getRowCount(): int
    {
        return $this->success_rows;
    }

    public function rules(): array
    {
        if($this->listId == null) {
            return [
                '*.email' => ['required', 'unique:contacts,email'],
                '*.first_name' => ['required'],
            ];
        } else {
            return [
                '*.email' => ['required'],
                '*.first_name' => ['required'],
            ];
        }
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
