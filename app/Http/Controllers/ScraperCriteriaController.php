<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScraperCriteria;
use Illuminate\Support\Facades\Artisan;
use App\DataTables\ScraperCriteriasDataTable;
use App\Models\Lists;
use App\Models\ScraperJob;

class ScraperCriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ScraperCriteriasDataTable $dataTable)
    {
        $endDate = ScraperJob::latest()->first()->end_at;
        return $dataTable->with(['endDate' => $endDate])->render('scraper-criterias.index');
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
        Lists::create([
            'name' => $request->keyword,
            'description' => $request->keyword . ' in ' . $request->location,
            'list_type_id' => 2,
            'user_id' => auth()->user()->id,
        ]);
        ScraperCriteria::create([
            'status' => 'In-Active',
            'keyword' => $request->keyword,
            'location' => $request->location,
            'limit' => $request->limit,
            'list_id' => Lists::latest()->first()->id,
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
        ScraperCriteria::where('status', 'Active')->update(['status' => 'In-Active']);
        ScraperCriteria::where('id', $request->id)->update(['status' => 'Active']);
        return redirect()->back()->with('success', 'Scraper Job Started Successfully');
    }

    public function stopScraper(Request $request)
    {
        ScraperCriteria::where('id', $request->id)->update(['status' => 'In-Active']);
        return redirect()->back()->with('success', 'Scraper Job Stopped Successfully');
    }

    public function startScraper()
    {
        ScraperCriteria::where('status', 'Active')->update(['status' => 'In-Active']);
        ScraperCriteria::where('id', request()->id)->update(['status' => 'Active']);
        Artisan::call('run:spider-scraper');
        return redirect()->back()->with('success', 'Scraper Completed');
    }
}
