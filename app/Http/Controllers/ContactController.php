<?php

namespace App\Http\Controllers;

use Exception;
use ReflectionClass;
use App\Models\Lists;
use App\Models\Source;
use App\Models\Contact;
use App\Models\Industry;
use App\Models\LeadStatus;
use Illuminate\Http\Request;
use App\Imports\ContactsImport;
use App\Imports\ContactExcelImport;
use Maatwebsite\Excel\Facades\Excel;
use App\DataTables\ContactsDataTable;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ContactsDataTable $dataTable, Contact $contact)
    {
        $startDate = request()->startDate;
        $endDate = request()->endDate;
        $getList = request()->list;
        $lists = Lists::all();
        $industries = Industry::all();
        $leadstatuses = LeadStatus::all();
        $sources = Source::all();
        return $dataTable->with(['getList' => $getList, 'startDate' => $startDate, 'endDate' => $endDate])->render('contacts.index', compact('leadstatuses', 'industries', 'contact', 'lists', 'getList', 'startDate', 'endDate', 'sources'));
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
                Contact::whereBetween('id', [$from, $to])->delete();
                return back()->with('success', 'Values Updated');
            }
            if ($get_bulk_column == 'lead_status_id') {
                Contact::whereBetween('id', [$from, $to])->update([$get_bulk_column => $get_lead]);
                return back()->with('success', 'Values Updated');
            }
            if ($get_bulk_column == 'industry_id') {
                Contact::whereBetween('id', [$from, $to])->update([$get_bulk_column => $get_industry]);
                return back()->with('success', 'Values Updated');
            }
            if ($get_bulk_column == 'list_id') {
                Contact::whereBetween('id', [$from, $to])->update([$get_bulk_column => $getList]);
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
            Contact::whereIn('id', $bulk_comma_record)->delete();
            return;
        }
        if ($get_bulk_column == 'lead_status_id') {
            Contact::whereIn('id', $bulk_comma_record)->update([$get_bulk_column => $get_lead]);
            return;
        }
        if ($get_bulk_column == 'industry_id') {
            Contact::whereIn('id', $bulk_comma_record)->update([$get_bulk_column => $get_industry]);
            return;
        }
        if ($get_bulk_column == 'list_id') {
            Contact::whereIn('id', $bulk_comma_record)->update([$get_bulk_column => $getList]);
            return;
        }
        else {
            try {
                Contact::whereIn('id', $bulk_comma_record)->update([$get_bulk_column => $get_reach]);
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
        $sources = Source::all();
        $list = request()->list;
        return view('contacts.create', compact('list', 'lists', 'sources'));
    }

    public function addContact(Request $request)
    {
        $this->validate($request, [
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required',
            'source_id' => 'required',
            'listId' => 'required',
        ]);

        $findcontact = Contact::where('email', $request->email)->first();

        if ($findcontact == NULL) {
            Contact::create([
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
                'linkedIn_profile' => $request->linkedin_profile,
                'industry_id' => $request->industry_id,
                'lead_status_id' => $request->lead_status_id,
                'source_id' => $request->source_id,
                'lists_id' => $request->listId,
            ]);
            return back()->with('success', 'Contact Added Successfully');
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
        $this->validate($request, [
            'listId' => 'required',
            'sourceId' => 'required',
        ]);
        $listId = $request->listId;
        $sourceId = $request->sourceId;
        $file = $request->file('csv_file')->store('import');
        try {
            $collection = Excel::toCollection(new ContactExcelImport, $file);
        } catch (Exception $e) {
            return back()->with('error', 'Please make sure the file is correct');
        }
        $get_contact_heading = $collection[0][0]->toArray();
        $check_columns = [
            'first_name',
            'last_name',
            'title',
            'company',
            'email',
            'phone',
            'country',
            'state',
            'city',
            'industry',
            'linkedin_profile'
        ];
        return view('contacts.check-columns', compact('get_contact_heading', 'check_columns', 'file', 'listId', 'sourceId'));
    }

    public function mapHeadings(Request $request)
    {
        $removeNull = array_filter($request->columns, function ($value) {
            return $value !== 'NULL';
        });
        $checkDuplicate = array_unique($removeNull);
        $file = storage_path('app/' . $request->file);
        if (count($checkDuplicate) != count($removeNull)) {
            return back()->with('error', 'Please make sure the mapped columns are not used more than once');
        } else {
            $import = new ContactsImport($request->sourceId, $request->listId, $request->columns[0], $request->columns[1], $request->columns[2], $request->columns[3], $request->columns[4], $request->columns[5], $request->columns[6], $request->columns[7], $request->columns[8], $request->columns[9], $request->columns[10]);
            ini_set('max_execution_time', '600');
            try {
                $import->import($file);
            } catch (Exception $e) {
                return back()->with('error', 'Please make sure the file is correct');
            }
            $importFailures = $import->failures();
            $getRow = $import->getRowCount();
            if ($importFailures->count() > 0) {
                return view('contacts.import-failuers', compact('importFailures', 'getRow'));
            } else {
                return redirect()->back()->with('success', $getRow . ' Contacts Added Successfully');
            }
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
        $sources = Source::all();
        return view('contacts.edit', compact('contact', 'industries', 'leadstatuses', 'sources'));
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
        return redirect()->route('contacts.index')->with('success', 'Contact deleted successfully');
    }

    public function shiftToMBL($unsubLink)
    {
        $decoded_email = base64_decode($unsubLink);
        $contacts = Contact::where('email', $decoded_email)->first();
        $contacts->lists_id = 1;
        $contacts->update();
        return redirect()->back()->with('success', 'Contact shifted to MBL successfully');
    }
}
