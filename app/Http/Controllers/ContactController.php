<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Industry;
use App\Models\LeadStatus;
use Illuminate\Http\Request;
use App\DataTables\ContactsDataTable;
use App\Repositories\ContactRepositoryInterface;

class ContactController extends Controller
{

    protected $contactRepository;
    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ContactsDataTable $dataTable, Contact $contact)
    {
        $leadstatuses = LeadStatus::all();
        return $dataTable->render('contacts.index', compact('leadstatuses'));
    }

    public function filterdata(Request $request)
    {
        $result = Contact::whereBetween('id', [$request->from, $request->to])
                    ->get();
                    foreach($result as $results){
                        $result1 = Contact::find($results->id);
                        $result1->reached_count = $request->reached_count;
                        $result1->save();
                    }
                    return back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->contactRepository->import($request);

        return redirect()->route('contacts.index')->with('success', 'File Imported Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        return view('contacts.show',compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        $leadstatuses = LeadStatus::all();
        $industries = Industry::all();
        return view('contacts.edit',compact('contact','industries','leadstatuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        $input = $request->all();
        $contact->update($input);
        return redirect()->route('contacts.index')->with('success', 'Contact updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return redirect()->route('contacts.index')->with('success', 'Contact deleted successfully');
    }
}
