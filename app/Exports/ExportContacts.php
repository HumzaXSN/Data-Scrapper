<?php

namespace App\Exports;

use App\Models\Contact;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportContacts implements FromCollection, ShouldAutoSize, WithMapping, WithHeadings
{
    use Exportable;

    protected $listId;

    public function __construct($listId)
    {
        $this->listId = $listId;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Contact::where('lists_id', $this->listId)->get();
    }

    public function map($contact): array
    {
        return [
            $contact->first_name,
            $contact->last_name,
            $contact->title,
            $contact->company,
            $contact->phone,
            $contact->email,
            URL::to('unsubscribe-email') . '/' . $contact->unsub_link,
            $contact->source->name ?? NULL,
            $contact->country,
            $contact->city,
            $contact->state,
            $contact->linkedIn_profile,
            $contact->industry->name ?? NULL
        ];
    }

    public function headings(): array
    {
        return [
            'First Name',
            'Last Name',
            'Title',
            'Company',
            'Phone',
            'Email',
            'Unsubscribe Link',
            'Source',
            'Country',
            'City',
            'State',
            'LinkedIn Profile',
            'Industry'
        ];
    }
}
