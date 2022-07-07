<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScraperCriteria;
use Illuminate\Support\Facades\Artisan;
use App\DataTables\ScraperCriteriasDataTable;
use App\Exports\ExportBusiness;
use App\Models\Lists;
use Carbon\Carbon;

class ScraperCriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ScraperCriteriasDataTable $dataTable)
    {
        return $dataTable->render('scraper-criterias.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $list = Lists::create([
            'name' => $request->keyword . ' in ' . $request->location . ' ' . Carbon::now()->format('d-m-Y H:i:s'),
            'description' => $request->keyword . ' in ' . $request->location . ' ' . Carbon::now()->format('d-m-Y H:i:s'),
            'list_type_id' => 2,
            'user_id' => auth()->user()->id,
        ]);
        ScraperCriteria::create([
            'status' => 'In-Active',
            'keyword' => $request->keyword,
            'location' => $request->location,
            'limit' => $request->limit,
            'lists_id' => $list->id,
        ]);
        return redirect()->route('scraper-criterias.index')->with('success', 'Criteria Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  by going to the page
     * @return \Illuminate\Http\Response
     */
    public function show(ScraperCriteria $scraperCriteria)
    {
        return view('scraper-criterias.show', compact('scraperCriteria'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  going to the page first
     * @return \Illuminate\Http\Response
     */
    public function edit()
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
    public function update(Request $request, ScraperCriteria $scraperCriteria)
    {
        $input = $request->except(['_token', '_method']);
        $scraperCriteria->update($input);
        return redirect()->route('scraper-criterias.index')->with('success', 'Criteria updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ScraperCriteria $scraperCriteria)
    {
        //
    }

    public function runScraper(Request $request)
    {
        ScraperCriteria::where('id', $request->id)->update(['status' => 'Active']);
        return redirect()->back()->with('success', 'Scraper Job Activated Successfully');
    }

    public function stopScraper(Request $request)
    {
        ScraperCriteria::where('id', $request->id)->update(['status' => 'In-Active']);
        return redirect()->back()->with('success', 'Scraper Job In-Activated Successfully');
    }

    public function startScraper()
    {
        $getData = ScraperCriteria::where('id', request()->id)->first();
        Artisan::call('run:google-businesses-scraper', [
            'keyword' => $getData->keyword,
            'city' => $getData->location,
            'limit' => $getData->limit,
            'criteriaId' => $getData->id
        ]);
        return redirect()->back()->with('success', 'Scraper Completed');
    }

    public function exportBusiness(Request $request)
    {
        ini_set('memory_limit', '256M');
        $getGoogleBusinessId = $request->getGoogleBusinessId;
        $googleBusinessId = $request->googleBusinessId;
        $googleBusinessCompany = $request->googleBusinessCompany;
        $getJobBusinessesId = $request->getJobBusinessesId;
        $getScraperCriteriaDetail = $request->getScraperCriteriaDetail;
        $getScraperCriteriaDetail = ucwords($getScraperCriteriaDetail);
        $getCriteriaId = $request->getCriteriaId;
        $getCriteriaDetail = $request->getCriteriaDetail;
        $getCriteriaDetail = ucwords($getCriteriaDetail);
        if (isset($getCriteriaId)) {
            return (new ExportBusiness($getCriteriaId, $getJobBusinessesId, $googleBusinessId, $getGoogleBusinessId))->download('Criteria: ' . $getCriteriaDetail . ' ' . Carbon::now() . '.xlsx');
        } else if (isset($getJobBusinessesId)) {
            return (new ExportBusiness($getCriteriaId, $getJobBusinessesId, $googleBusinessId, $getGoogleBusinessId))->download('Job: ' . $getScraperCriteriaDetail . ' ' . Carbon::now() . '.xlsx');
        } else if (isset($googleBusinessId)) {
            return (new ExportBusiness($getCriteriaId, $getJobBusinessesId, $googleBusinessId, $getGoogleBusinessId))->download('Business: ' . $googleBusinessCompany . ' ' . Carbon::now() . '.xlsx');
        } else if (isset($getGoogleBusinessId)) {
            return (new ExportBusiness($getCriteriaId, $getJobBusinessesId, $googleBusinessId, $getGoogleBusinessId))->download('Multiple Business Data ' . Carbon::now() . '.xlsx');
        } else {
            return redirect()->back()->with('error', 'No Business was Selected');
        }
    }
}
