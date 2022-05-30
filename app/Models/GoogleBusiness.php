<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleBusiness extends Model
{
    use HasFactory;

    protected $fillable = [
        'company',
        'phone',
        'email',
        'address',
        'website',
        'scraper_job_id'
    ];

    public function scraperJob()
    {
        return $this->belongsTo(ScraperJob::class);
    }

    public function Notes()
    {
        return $this->belongsToMany(Note::class);
    }
}
