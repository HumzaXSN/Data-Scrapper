<?php

namespace App\Http\Controllers;

use Exception;
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
        $industries = Industry::all();
        $leadstatuses = LeadStatus::all();
        return $dataTable->render('contacts.index', compact('leadstatuses','industries'));
    }

    public function bulkupdate(Request $request)
    {
        $bulk_range = $request->get('record_range');
        $get_bulk_column = $request->get('bulk_update_column');
        $get_lead = $request->lead_status_id;
        $get_industry = $request->industry_id;
        $get_reach = $request->reached_count;
        if( strpos($bulk_range , ',') != false ) {
            self::bulkcommaupdate($bulk_range, $get_bulk_column, $get_lead, $get_industry, $get_reach);
            return back()->with('success','Values Updated');
        }
        else{
        $bulk_range_record = explode('-',$bulk_range);
        $from = $bulk_range_record[0];
        try{
            $to = $bulk_range_record[1];
        }
        catch(Exception $e){
            $to = $from;
        }
        if ( $get_bulk_column == 'delete' ) {
            $result = Contact::whereBetween('id', [$from, $to])->get();
            foreach($result as $del){
                $del->delete();
            }
            return back()->with('success','Values Updated');
        }
        if ( $get_bulk_column == 'lead_status_id') {
            $lead_update = Contact::whereBetween('id', [$from, $to])->update([$get_bulk_column => $get_lead]);
            return back()->with('success','Values Updated');
        }
        if ( $get_bulk_column == 'industry_id') {
            $industry_update = Contact::whereBetween('id', [$from, $to])->update([$get_bulk_column => $get_industry]);
            return back()->with('success','Values Updated');
        }
        else {
            try {
                $result = Contact::whereBetween('id', [$from, $to])->update([$get_bulk_column => $get_reach]);
                return back()->with('success','Values Updated');
            }
            catch(\Exception $e){
                report($e);
                return back()->with('error','Values not Updated');
            }
        }
        }
    }

    public static function bulkcommaupdate($bulk_range, $get_bulk_column, $get_lead, $get_industry, $get_reach)
    {
        $bulk_comma_record = explode(',', $bulk_range);
        if ( $get_bulk_column == 'delete' ) {
            $result = Contact::whereIn('id', $bulk_comma_record)->get();
            foreach($result as $del){
                $del->delete();
            }
            return;
        }
        if ( $get_bulk_column == 'lead_status_id') {
            $lead_update = Contact::whereIn('id', $bulk_comma_record)->update([$get_bulk_column => $get_lead]);
            return;
        }
        if ( $get_bulk_column == 'industry_id') {
            $industry_update = Contact::whereIn('id', $bulk_comma_record)->update([$get_bulk_column => $get_industry]);
            return;
        }
        else {
            try {
                $result = Contact::whereIn('id', $bulk_comma_record)->update([$get_bulk_column => $get_reach]);
                return;
            }
            catch(\Exception $e){
                report($e);
                return back()->with('error','Values not Updated');
            }
        }
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

    public function addContact(Request $request)
    {
        $this->validate($request, [
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required',
            'source' => 'required'
        ]);

        $findcontact = Contact::where('email',$request->email)->first();

        if($findcontact == NULL) {
            $contact = new Contact;
            $contact->first_name = $request->input('fname');
            $contact->last_name = $request->input('lname');
            $contact->title = $request->input('title');
            $contact->company = $request->input('company');
            $contact->email = $request->input('email');
            $contact->country = $request->input('country');
            $contact->state = $request->input('state');
            $contact->city = $request->input('city');
            $contact->phone = $request->input('phone');
            $contact->reached_platform = $request->input('reach_platform');
            $contact->linkedin_profile = $request->input('linkedin_profile');
            $contact->industry_id = $request->input('industry_id');
            $contact->lead_status_id = $request->input('lead_status_id');
            $contact->source = $request->input('source');
            $contact->save();
            return back()->with('success','Contact Added Successfully');
        }
        else{
            return back()->with('error','Contact Email already exists');
        }
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

    public function deleteSelectedContacts(Request $request)
    {
        $contact_ids = $request->contacts_ids;
        Contact::whereIn('id', $contact_ids)->delete();
        return response()->json(['code'=>1, 'msg'=>'Selected Contacts deleted Successfully']);
    }
}
