<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Contact;
use App\Models\Industry;
use App\Models\ScraperJob;
use Illuminate\Http\Request;
use App\Models\DecisionMaker;
use App\Models\GoogleBusiness;
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
        $getValidate = DecisionMaker::where('validate', 1)->get();
        $getValidateCount = count($getValidate);
        $getJobBusinesses = request()->getJobBusinesses;
        $getBusiness = request()->showBusiness;
        return $dataTable->with(['getBusiness' => $getBusiness, 'getJobBusinesses' => $getJobBusinesses, 'getValidateCount' => $getValidateCount])->render('google-businesses.index');
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
        return view('google-businesses.show',compact('googleBusiness'));
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
        $googleBusiness->delete();
        return redirect()->back()->with('success', 'Business deleted successfully');
    }

    public function deleteBusinessName(Request $request)
    {
        $status = DecisionMaker::find($request->id)->delete();
        if($status) {
            return response()->json(['success' => 'Business Decision Maker deleted successfully'], 200);
        }
    }

    public function validateBusinessContact(Request $request)
    {
        $status = DecisionMaker::where('id', $request->id)->update(['validate' => 1]);
        if($status) {
            return response()->json(['success' => 'Business Decision Maker validated successfully'], 200);
        }
    }

    public function insertBusinessContact()
    {
        $decisionMakers = DecisionMaker::with('googleBusiness')->where('validate', 1)->get();
        $contactData = [];
        if(count($decisionMakers) > 0) {
            foreach ($decisionMakers as $decisionMaker) {
                if(!Contact::where('first_name', $decisionMaker->name)->exists()) {
                    $email = str_replace(' ', '', $decisionMaker->name);
                    $industy = Industry::firstOrCreate(['name' => $decisionMaker->googleBusiness->industry]);
                    $scraperJob = ScraperJob::with('scraperCriteria')->findOrFail($decisionMaker->googleBusiness->scraper_job_id);
                    $contactData[] =  [
                        'first_name' => $decisionMaker->name,
                        'linkedIn_profile' => $decisionMaker->url,
                        'phone' => $decisionMaker->googleBusiness->phone,
                        'city' => $scraperJob->scraperCriteria->location,
                        'company' => $decisionMaker->googleBusiness->company,
                        'email' => $email . '@example.com',
                        'unsub_link' => base64_encode($email . '@example.com'),
                        'source' => 1,
                        'status' => 1,
                        'created_at' => now()->format('Y-m-d H:i:s'),
                        'updated_at' => now()->format('Y-m-d H:i:s'),
                        'industry_id' => $industy->id,
                    ];
                } else {
                    return redirect()->back()->with('error', 'Business Already exists');
                }
            }
        } else {
            return redirect()->back()->with('error', 'Validated Data already entered');
        }
        DecisionMaker::where('validate', 1)->update(['validate' => 2]);
        Contact::insert($contactData);
        return redirect()->back()->with('success', 'Business Decision Maker inserted successfully');
    }

}
