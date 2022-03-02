<?php

namespace App\Imports;

use App\Models\Contact;
use Exception;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\ImportFailed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Validators\ValidationException;

class ContactsImport implements ToModel, WithHeadingRow, WithValidation ,WithBatchInserts ,WithChunkReading, SkipsOnError, SkipsOnFailure
{
    private $success_rows = 0;
    use Importable, SkipsFailures, SkipsErrors;
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
        ++$this->success_rows;
        // $json = public_path('json/US_States_and_Cities.json');
        // $contacts = json_decode(file_get_contents($json), true);
        // $states = array();
        // $state_flag = false;
        // $city_flag = false;
        // if(array_key_exists($row['state'], $contacts))
        // {
        //     $state_flag = true;
        //     $states = $contacts[$row['state']];
        //     if(in_array($row['city'], $states))
        //     {
        //         $city_flag = true;
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
                    'linkedIn_profile' => $row['linkedin_profile'] ?? NULL,
                    'source' => $this->source,
                ]);
    }
        //         if(!$city_flag)
        //         {
        //             // $row = 'City not found';
        //             $error[] = 'Could not find city';
        //             throw new \Maatwebsite\Excel\Validators\ValidationException(\Illuminate\Validation\ValidationException::withMessages($error), $row['city']);
        //         }
        //     }
        // if(!$state_flag)
        // {
        //     // $row = 'State not found';
        //     $error[] = 'Could not find state';
        //     throw new \Maatwebsite\Excel\Validators\ValidationException(\Illuminate\Validation\ValidationException::withMessages($error), [$row['state']]);
        // }
        // }
    // }

    // public function breakstateloop()
    // {
    //     dd(123);
    //     // return view('contacts.create')->with('error', 'Invalid State Entered');
    // }

    // public function breakcityloop()
    // {
    //     dd(456);
    //     // return view('contacts.create')->with('error', 'Invalid City Entered');
    // }

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
