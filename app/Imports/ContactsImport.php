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
        $json = public_path('json/US_States_and_Cities.json');
        $contacts = json_decode(file_get_contents($json), true);
        $states = array();
        foreach($contacts as $key=>$contact)
        {
            try {
            if($key == $row['state'])
            {
                $states = $contact;
                if(in_array($row['city'], $states))
                {
                   return new Contact([
                        'id' => $row['id'],
                        'first_name' => $row['firstname'],
                        'last_name' => $row['lastname'],
                        'title' => $row['title'],
                        'company' => $row['company'],
                        'phone' => $row['phone'] ?? NULL,
                        'email' => $row['email'],
                        'city' => $row['city'] ?? NULL,
                        'state' => $row['state'] ?? NULL,
                        'reached_platform' => $row['reachedplatform'] ?? NULL,
                        'lead_status_id' => $row['leadstatusid'] ?? NULL,
                        'source' => $this->source,
                    ]);
                }
                else {
                    return back()->with('error', 'Invalid City Entered');
                }
            }
            }
            catch(Throwable $e) {
                report($e);
                return back()->with('error', 'Invalid State Entered');
            }
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


    public function onError(Throwable $error)
    {
        # code...
    }
}
