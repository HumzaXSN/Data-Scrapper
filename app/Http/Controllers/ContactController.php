<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Contact;
use App\Models\Industry;
use App\Models\LeadStatus;
use Illuminate\Http\Request;
use App\Imports\ContactsImport;
use App\DataTables\ContactsDataTable;
use App\Models\Lists;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ContactsDataTable $dataTable, Contact $contact)
    {
        $getList = request()->list;
        $lists = Lists::all();
        $industries = Industry::all();
        $leadstatuses = LeadStatus::all();
        return $dataTable->with('getList', $getList)->render('contacts.index', compact('leadstatuses', 'industries', 'contact', 'lists', 'getList'));
    }

    public function bulkupdate(Request $request)
    {
        $bulk_range = $request->get('record_range');
        $get_bulk_column = $request->get('bulk_update_column');
        $get_lead = $request->lead_status_id;
        $get_industry = $request->industry_id;
        $get_reach = $request->reached_count;
        $getList = $request->list_id;
        if (strpos($bulk_range, ',') != false) {
            self::bulkcommaupdate($bulk_range, $get_bulk_column, $get_lead, $get_industry, $get_reach, $getList);
            return back()->with('success', 'Values Updated');
        } else {
            $bulk_range_record = explode('-', $bulk_range);
            $from = $bulk_range_record[0];
            try {
                $to = $bulk_range_record[1];
            } catch (Exception $e) {
                $to = $from;
            }
            if ($get_bulk_column == 'delete') {
                $result = Contact::whereBetween('id', [$from, $to])->get();
                foreach ($result as $del) {
                    $del->delete();
                    $del->lists()->detach();
                }
                return back()->with('success', 'Values Updated');
            }
            if ($get_bulk_column == 'lead_status_id') {
                $lead_update = Contact::whereBetween('id', [$from, $to])->update([$get_bulk_column => $get_lead]);
                return back()->with('success', 'Values Updated');
            }
            if ($get_bulk_column == 'industry_id') {
                $industry_update = Contact::whereBetween('id', [$from, $to])->update([$get_bulk_column => $get_industry]);
                return back()->with('success', 'Values Updated');
            }
            if ($get_bulk_column == 'list_id') {
                $getContact = Contact::whereBetween('id', [$from, $to])->get();
                if($getList == 1) {
                    foreach ($getContact as $contact) {
                        $contact->lists()->sync($getList);
                    }
                } else {
                    foreach ($getContact as $contact) {
                        $contact->lists()->syncWithoutDetaching($getList);
                        $contact->lists()->detach(1);
                    }
                }
                return back()->with('success', 'Values Updated');
            }
            else {
                try {
                    $result = Contact::whereBetween('id', [$from, $to])->update([$get_bulk_column => $get_reach]);
                    return back()->with('success', 'Values Updated');
                } catch (\Exception $e) {
                    report($e);
                    return back()->with('error', 'Values not Updated');
                }
            }
        }
    }

    public static function bulkcommaupdate($bulk_range, $get_bulk_column, $get_lead, $get_industry, $get_reach, $getList)
    {
        $bulk_comma_record = explode(',', $bulk_range);
        if ($get_bulk_column == 'delete') {
            $result = Contact::whereIn('id', $bulk_comma_record)->get();
            foreach ($result as $del) {
                $del->delete();
                $del->lists()->detach();
            }
            return;
        }
        if ($get_bulk_column == 'lead_status_id') {
            $lead_update = Contact::whereIn('id', $bulk_comma_record)->update([$get_bulk_column => $get_lead]);
            return;
        }
        if ($get_bulk_column == 'industry_id') {
            $industry_update = Contact::whereIn('id', $bulk_comma_record)->update([$get_bulk_column => $get_industry]);
            return;
        }
        if ($get_bulk_column == 'list_id') {
            $getContact = Contact::whereIn('id', $bulk_comma_record)->get();
            if($getList == 1) {
                foreach ($getContact as $contact) {
                    $contact->lists()->sync($getList);
                }
            } else {
                foreach ($getContact as $contact) {
                    $contact->lists()->syncWithoutDetaching($getList);
                    $contact->lists()->detach(1);
                }
            }
            return;
        }
        else {
            try {
                $result = Contact::whereIn('id', $bulk_comma_record)->update([$get_bulk_column => $get_reach]);
                return;
            } catch (\Exception $e) {
                report($e);
                return back()->with('error', 'Values not Updated');
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
        $lists = Lists::all();
        $list = request()->list;
        return view('contacts.create', compact('list', 'lists'));
    }

    public function addContact(Request $request)
    {
        $this->validate($request, [
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required',
            'source' => 'required'
        ]);

        $findcontact = Contact::where('email', $request->email)->first();

        if ($findcontact == NULL) {
            $contact = Contact::create([
                'first_name' => $request->fname,
                'last_name' => $request->lname,
                'title' => $request->title,
                'company' => $request->company,
                'email' => $request->email,
                'unsub_link' => base64_encode($request->email),
                'country' => $request->country,
                'state' => $request->state,
                'city' => $request->city,
                'phone' => $request->phone,
                'reached_platform' => $request->reach_platform,
                'linkedin_profile' => $request->linkedin_profile,
                'industry_id' => $request->industry_id,
                'lead_status_id' => $request->lead_status_id,
                'source' => $request->source,
            ]);
            if ($request->listId != NULL) {
                $contact->lists()->syncWithoutDetaching($request->listId);
                return back()->with('success', 'Contact Added Successfully');
            }
            else {
                return back()->with('success', 'Contact Added Successfully');
            }
        } else {
            return back()->with('error', 'Contact Email already exists');
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
        $industry = Industry::all();
        $file = $request->file('csv_file');
        $import = new ContactsImport($request->source, $request->listId);
        ini_set('max_execution_time', '600');
        $import->import($file);
        $importFailures = $import->failures();
        $errorsMsgs = [];
        $failureRows = [];
        foreach ($import->failures() as $failure) {
            array_push($errorsMsgs, $failure->attribute());
        }
        foreach($importFailures as $failure) {
            if(array_key_exists($failure->row(), $failureRows))
            {
                $failureRows[$failure->row()] = [$failure->values(),'yes'];
            }
            else
            {
                $failureRows[$failure->row()] = [$failure->values()];
            }
        }
        if (count($importFailures) > 0) {
            return view('contacts.provisional')->with(['failures' => $failureRows, 'source' => $request->source, 'industry' => $industry, 'success_row' => $import->getRowCount(), 'errorsMsgs' => $errorsMsgs, 'listId' => $request->listId]);
        } else {
            if(empty($request->listId))
            return redirect()->route('contacts.index')->with('success', $import->getRowCount() . ' Contacts Added Successfully');
            else
            return redirect()->back()->with('success', $import->getRowCount() . ' Contacts Added Successfully');
        }
    }

    public function provisionalPage(Request $request)
    {
        $industry = Industry::all();
        $fname = $request->fname; $lname = $request->lname; $email = $request->email; $title = $request->title; $company = $request->company; $country = $request->country; $state = $request->state; $city = $request->city; $phone = $request->phone; $linkedin_profile = $request->linkedin_profile; $industry_id = $request->industry_id; $source = $request->source;
        $arr = [];
        for ($i = 0; $i < count($fname); $i++) {
            $bulk_contact_insert = [
                'first_name' => $fname[$i],
                'last_name' => isset($lname[$i]) ?: null,
                'title' => isset($title[$i]) ?: null,
                'company' => isset($company[$i]) ?: null,
                'email' => $email[$i],
                'unsub_link' => base64_encode($email[$i]),
                'country' => isset($country[$i]) ?: null,
                'state' => isset($state[$i]) ?: null,
                'city' => isset($city[$i]) ?: null,
                'phone' => isset($phone[$i]) ?: null,
                'linkedin_profile' => isset($linkedin_profile[$i]) ?: null,
                'industry_id' => isset($industry_id[$i]) ?: null,
                'source' => isset($source[$i]) ?: null
            ];
            if($fname[$i]!= '' && !Contact::where('email', $email[$i])->exists()) {
                Contact::create($bulk_contact_insert);
                $getContact = Contact::where('email', $email[$i])->first();
                $getContact->lists()->syncWithoutDetaching($request->listId);
            } else {
                array_push($arr, $bulk_contact_insert);
            }
            $getEmail = Contact::where('email', $email[$i])->get();
        }
        if ($arr == NULL) {
            if(empty($request->listId))
            return redirect()->route('contacts.index')->with('success', 'Contact added successfully');
            else
            return redirect()->route('lists.show', $request->listId)->with('success', 'Contact added successfully');
        } else {
            return view('contacts.provisional',compact('arr', 'industry'))->with(['listId' => $request->listId, 'getEmail' => $getEmail]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        return view('contacts.show', compact('contact'));
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
        return view('contacts.edit', compact('contact', 'industries', 'leadstatuses'));
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
        $input = $request->except(['_token', '_method']);
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
        $contact->lists()->detach();
        return redirect()->route('contacts.index')->with('success', 'Contact deleted successfully');
    }

    public function shiftToMBL()
    {
        $contacts = Contact::where('id', request()->id)->first();
        $contacts->lists()->sync(1);
        return redirect()->back()->with('success', 'Contact shifted to MBL successfully');
    }
}
