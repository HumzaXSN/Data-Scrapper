<?php

namespace App\Http\Controllers;

use App\DataTables\ScraperJobsDataTable;
use Illuminate\Http\Request;

class ScraperJobController extends Controller
{
    public function index(ScraperJobsDataTable $dataTable)
    {
        return $dataTable->render('scraper-jobs.index');
    }
}
