<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Contact;
use App\Models\Industry;
use App\Models\ScraperJob;
use Illuminate\Http\Request;
use App\Models\DecisionMaker;
use App\Models\GoogleBusiness;
use Illuminate\Support\Facades\DB;
use App\Models\DecisionMakersEmails;
use App\DataTables\GoogleBusinessesDataTable;

class GoogleBusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GoogleBusinessesDataTable $dataTable)
    {
        $getJobBusinesses = request()->getJobBusinesses;
        $getBusiness = request()->showBusiness;
        return $dataTable->with(['getBusiness' => $getBusiness, 'getJobBusinesses' => $getJobBusinesses])->render('google-businesses.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(GoogleBusiness $googleBusiness)
    {
        return view('google-businesses.show', compact('googleBusiness'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(GoogleBusiness $googleBusiness)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        $getdata = $googleBusiness->decisionMakers;
        foreach ($getdata as $data) {
            $data->DecisionMakerEmails()->delete();
        }
        $googleBusiness->decisionMakers()->delete();
        $googleBusiness->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        return redirect()->back()->with('success', 'Business deleted successfully');
    }

    public function deleteBusinessName(Request $request)
    {
        DecisionMaker::find($request->id)->delete();
        DecisionMakersEmails::where('decision_maker_id', $request->id)->delete();
        return redirect()->back()->with('success', 'Decision Maker deleted successfully');
    }

    public function validateBusinessContact(Request $request)
    {
        $checkEmail = DecisionMakersEmails::where('decision_maker_id', $request->id)->exists();
        if ($checkEmail) {
            $status = DecisionMaker::where('id', $request->id)->update(['validate' => 1]);
            if ($status) {
                return response()->json(['success' => 'Business Decision Maker validated successfully'], 200);
            }
        } else {
            return response()->json(['error' => 'Business Decision Maker not found'], 404);
        }
    }

    public function deleteBusinessEmail(Request $request)
    {
        $status = DecisionMakersEmails::find($request->id)->delete();
        if ($status) {
            return response()->json(['success' => 'Business Decision Maker email deleted successfully'], 200);
        }
    }

    public function successBusinessEmail(Request $request)
    {
        $status = DecisionMakersEmails::where('id', $request->id)->update(['email' => $request->email]);
        if ($status) {
            return response()->json(['success' => 'Decision Maker Email Updated successfully'], 200);
        }
    }

    public function successNewBusinessEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);
        if ($request->email != null) {
            $status = DecisionMakersEmails::create([
                'decision_maker_id' => $request->id,
                'email' => $request->email,
            ]);
            if ($status) {
                return response()->json(['success' => 'Decision Maker Email Added successfully'], 200);
            }
        } else {
            return response()->json(['error' => 'Email is required'], 404);
        }
    }

    public function successEditBusinessEmail(Request $request)
    {
        $status = DecisionMakersEmails::where('id', $request->id)->update(['email' => $request->email]);
        if ($status) {
            return response()->json(['success' => 'Decision Maker Email Updated successfully'], 200);
        }
    }

    public function addDecisionMaker(Request $request) {
            $this->validate($request, [
            'name' => 'required',
            'url' => 'required',
            'email' => 'required|email',
        ]);
        if ($request->name != null || $request->url != null) {
            $status = DecisionMaker::create([
                'name' => $request->name,
                'url' => $request->url,
                'validate' => 0,
                'google_business_id' => $request->id,
            ]);
            DecisionMakersEmails::create([
                'email' => $request->email,
                'decision_maker_id' => $status->id,
            ]);
            if ($status) {
                return response()->json(['success' => 'Decision Maker Added successfully',
                    'decision_maker_id' => $status->id ], 200);
            }
        } else {
            return response()->json(['error' => 'Name, URL and Email are required'], 404);
        }
    }

    public function insertBusinessContact()
    {
        $googleBusinessId = request()->googleBusinessId;
        $decisionMakers = DecisionMaker::with('googleBusiness', 'decisionMakerEmails')->where([['validate', 1], ['google_business_id', $googleBusinessId]])->get();
        $contactData = [];
        if (count($decisionMakers) > 0) {
            foreach ($decisionMakers as $decisionMaker) {
                $name1 = $decisionMaker->name;
                $name2 = explode('-', $name1);
                $name3 = explode(' ', $name2[0]);
                $getdata = sizeof($name3) - 2;
                $lastName = $name3[$getdata];
                $firstName = $name3[0];
                $title = $name2[1];
                $emails = $decisionMaker->decisionMakerEmails->pluck('email')->toArray();
                foreach ($emails as $email) {
                    if (!Contact::where('email', $email)->exists()) {
                        $industy = Industry::firstOrCreate(['name' => $decisionMaker->googleBusiness->industry]);
                        $scraperJob = ScraperJob::with('scraperCriteria')->findOrFail($decisionMaker->googleBusiness->scraper_job_id);
                        $contactData[] =  [
                            'first_name' => $firstName,
                            'last_name' => $lastName,
                            'title' => $title,
                            'linkedIn_profile' => $decisionMaker->url,
                            'phone' => $decisionMaker->googleBusiness->phone,
                            'city' => $scraperJob->scraperCriteria->location,
                            'company' => $decisionMaker->googleBusiness->company,
                            'email' => $email,
                            'unsub_link' => base64_encode($email),
                            'source_id' => 3,
                            'status' => 1,
                            'created_at' => now()->format('Y-m-d H:i:s'),
                            'updated_at' => now()->format('Y-m-d H:i:s'),
                            'industry_id' => $industy->id,
                            'list_id' => $scraperJob->scraperCriteria->lists_id,
                        ];
                    } else {
                        return redirect()->back()->with('error', 'Email Already exists');
                    }
                }
            }
        } else {
            return redirect()->back()->with('error', 'Validated Data already entered');
        }
        DecisionMaker::where([['validate', 1], ['google_business_id', $googleBusinessId]])->update(['validate' => 2]);
        Contact::insert($contactData);
        return redirect()->back()->with('success', 'Business Decision Maker inserted successfully');
    }
}
