<?php

namespace App\Imports;

use App\Models\Contact;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithValidation;

class ContactsImport implements ToModel, WithHeadingRow, WithValidation ,WithBatchInserts ,WithChunkReading
{
    use Importable, SkipsFailures;
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
        // $json = public_path('json/US_States_and_Cities.json');
        // $contacts = json_decode(file_get_contents($json), true);
        // $states = array();
        // // $state_flag = false;
        // // $city_flag = false;
        //     if(array_key_exists($row['state'], $contacts))
        //     {
        //         // $state_flag = true;
        //         $states = $contacts[$row['state']];
        //         if(in_array($row['city'], $states))
        //         {
        //             // $city_flag = true;
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
            //     }
            //     // if(!$city_flag)
            //     // {
            //     //     self::breakcityloop();
            //     // }
            // }
        // if(!$state_flag)
        // {
        //     self::breakstateloop();
        // }
    }

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

    public function rules(): array
    {
        return [
            '*.email' => ['required', 'unique:contacts,email'],
        ];
    }

    // public function onError(Throwable $error)
    // {

    // }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    /**
    //  * @param Failure[] $failures
     */
    // public function onFailure(Failure ...$failures)
    // {
    //     // Handle the failures how you'd like.
    // foreach ($import->failures() as $failure) {
    //  $failure->row(); // row that went wrong
    //  $failure->attribute(); // either heading key (if using heading row concern) or column index
    //  $failure->errors(); // Actual error messages from Laravel validator
    //  $failure->values(); // The values of the row that has failed.
    // }

    // // public function onError(Throwable $error)
    // // {

    // // }
    // }
}
