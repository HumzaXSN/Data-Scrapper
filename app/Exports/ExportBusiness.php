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
    protected $getCriteriaId, $getJobBusinessesId, $googleBusinessId, $getGoogleBusinessId;

    public function __construct($getCriteriaId, $getJobBusinessesId, $googleBusinessId, $getGoogleBusinessId)
    {
        $this->getJobBusinessesId = $getJobBusinessesId;
        $this->getCriteriaId = $getCriteriaId;
        $this->googleBusinessId = $googleBusinessId;
        $this->getGoogleBusinessId = $getGoogleBusinessId;
    }

    public function view(): View
    {
        if (isset($this->getCriteriaId)) {
            $googleBusiness = GoogleBusiness::whereHas('scraperJob', function ($query) {
                $query->where('scraper_criteria_id', $this->getCriteriaId);
            })->get();
        } else if(isset($this->getJobBusinessesId)) {
            $googleBusiness = GoogleBusiness::where('scraper_job_id', $this->getJobBusinessesId)->get();
        } else if (isset($this->googleBusinessId)) {
            $googleBusiness = GoogleBusiness::where('id', $this->googleBusinessId)->get();
        } else if (isset($this->getGoogleBusinessId)) {
            return view('exports.google-business', [
                'googleBusinessesData' => $this->getGoogleBusinessId
            ]);
        }

        if (!isset($this->getGoogleBusinessId)) {
            return view('exports.google-business', [
                'googleBusinesses' => $googleBusiness
            ]);
        }
    }
}
