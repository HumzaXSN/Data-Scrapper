<?php

namespace App\Exports;

use App\Models\GoogleBusiness;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExportBusiness implements FromView, ShouldAutoSize
{
    use Exportable;
    protected $getCriteriaId, $getJobBusinessesId;

    public function __construct($getCriteriaId, $getJobBusinessesId)
    {
        $this->getJobBusinessesId = $getJobBusinessesId;
        $this->getCriteriaId = $getCriteriaId;
    }

    public function view(): View
    {
        if (isset($this->getCriteriaId)) {
            $googleBusiness = GoogleBusiness::whereHas('scraperJob', function ($query) {
                $query->where('scraper_criteria_id', $this->getCriteriaId);
            })->get();
        } else if($this->getJobBusinessesId) {
            $googleBusiness = GoogleBusiness::where('scraper_job_id', $this->getJobBusinessesId)->get();
        }

        return view('exports.google-business', [
            'googleBusinesses' => $googleBusiness
        ]);
    }
}