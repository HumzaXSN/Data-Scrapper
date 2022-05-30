<?php

namespace App\Http\Controllers;

use App\DataTables\ScraperJobsDataTable;
use Illuminate\Http\Request;

class ScraperJobController extends Controller
{
    public function index(ScraperJobsDataTable $dataTable)
    {
        $getJobs = request()->id;
        return $dataTable->with(['getJobs' => $getJobs])->render('scraper-jobs.index');
    }
}
