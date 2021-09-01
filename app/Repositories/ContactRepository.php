<?php

namespace App\Repositories;

use App\Imports\ContactsImport;
use Maatwebsite\Excel\Facades\Excel;


class ContactRepository implements ContactRepositoryInterface

{
    public function import($request)
    {
        Excel::import(new ContactsImport($request->source), $request->file('csv_file'));
    }
}


?>
