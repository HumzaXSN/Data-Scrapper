<?php

namespace App\Imports;

use Throwable;
use App\Models\Contact;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ContactsImport implements ToModel, WithHeadingRow, SkipsOnError, WithBatchInserts, WithChunkReading, ShouldQueue
{
    use SkipsErrors;
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
            if($key == $row['state'])
            {
                $states = $contact;
                if(in_array($row['city'], $states))
                {
                    if ($row['industry'] == 'Healthcare') {
                        $row['industry'] = '2';
                    }
                    else {
                        $row['industry'] = '3';
                    }
                    return new Contact([
                        'first_name' => $row['first_name'],
                        'last_name' => $row['last_name'],
                        'title' => $row['title'] ?? NULL,
                        'company' => $row['company'],
                        'email' => $row['email'],
                        'phone' => $row['phone'] ?? NULL,
                        'country' => $row['country'] ?? NULL,
                        'city' => $row['city'] ?? NULL,
                        'state' => $row['state'] ?? NULL,
                        'industry_id' => $row['industry'] ?? NULL,
                        'linkedin_profile' => $row['linkedin_profile'] ?? NULL,
                        'source' => $this->source,
                    ]);
                }
                else {
                    self::breakcityloop();
                }
            }
            else {
                self::breakstateloop();
            }
        }
    }

    public function breakstateloop()
    {
        return back()->with('error', 'Invalid State Entered');
    }

    public function breakcityloop()
    {
        return back()->with('error', 'Invalid City Entered');
    }

    public function rules(): array
    {
        return [
            '*.email' => ['email', 'unique:contacts, email'],
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


    public function onError(Throwable $error)
    {
        # code...
    }
}
