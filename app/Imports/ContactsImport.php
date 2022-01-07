<?php

namespace App\Imports;

use Throwable;
use App\Models\Contact;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ContactsImport implements ToModel, WithHeadingRow, SkipsOnError, WithBatchInserts, WithChunkReading, ShouldQueue
{
    protected $source;
    public function  __construct($source)
    {
        $this->source= $source;
    }


    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Contact([
            'first_name' => $row['firstname'],
            'last_name' => $row['lastname'],
            'title' => $row['title'],
            'company' => $row['company'],
            'phone' => $row['phone'] ?? NULL,
            'email' => $row['email'],
            'city' => $row['city'] ?? NULL,
            'state' => $row['state'] ?? NULL,
            'source' => $this->source,
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }


    public function onError(Throwable $error)
    {
        # code...
    }
}
