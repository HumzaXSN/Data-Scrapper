<?php

namespace App\Imports;

use App\Models\Contact;
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

class ContactsImport implements ToModel, WithHeadingRow, WithValidation ,WithBatchInserts ,WithChunkReading, SkipsOnError, SkipsOnFailure
{
    private $success_rows = 0;
    use Importable, SkipsFailures, SkipsErrors;
    protected $source;
    public function  __construct($source, $excelcolumns1, $excelcolumns2, $excelcolumns3, $excelcolumns4, $excelcolumns5, $excelcolumns6, $excelcolumns7, $excelcolumns8, $excelcolumns9, $excelcolumns10, $excelcolumns11)
    {
        $this->source= $source;
        $this->columns[0] = $excelcolumns1;
        $this->columns[1] = $excelcolumns2;
        $this->columns[2] = $excelcolumns3;
        $this->columns[3] = $excelcolumns4;
        $this->columns[4] = $excelcolumns5;
        $this->columns[5] = $excelcolumns6;
        $this->columns[6] = $excelcolumns7;
        $this->columns[7] = $excelcolumns8;
        $this->columns[8] = $excelcolumns9;
        $this->columns[9] = $excelcolumns10;
        $this->columns[10] = $excelcolumns11;
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
                    'first_name' => $this->columns[0] == "NULL" ? NULL : $row[$this->columns[0]],
                    'last_name' => $this->columns[1] == "NULL" ? NULL : $row[$this->columns[1]],
                    'title' => $this->columns[2] == "NULL" ? NULL : $row[$this->columns[2]],
                    'company' => $this->columns[3] == "NULL" ? NULL : $row[$this->columns[3]],
                    'email' => $this->columns[4] == "NULL" ? NULL : $row[$this->columns[4]],
                    'phone' => $this->columns[5] == "NULL" ? NULL : $row[$this->columns[5]],
                    'country' => $this->columns[6] == "NULL" ? NULL : $row[$this->columns[6]],
                    'city' => $this->columns[7] == "NULL" ? NULL : $row[$this->columns[7]],
                    'state' => $this->columns[8] == "NULL" ? NULL : $row[$this->columns[8]],
                    'industry_id' => $this->columns[9] == "NULL" ? NULL : $row[$this->columns[9]],
                    'linkedIn_profile' => $this->columns[10] == "NULL" ? NULL : $row[$this->columns[10]],
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
