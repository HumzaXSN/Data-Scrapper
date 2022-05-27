<?php

namespace App\Models;

use App\Models\ScraperCriteria;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScraperJob extends Model
{
    use HasFactory;
    protected $table = 'scraper_jobs';
    protected $fillable = [
        'ip',
        'url',
        'platform',
        'status',
        'message',
        'last_index',
        'scraper_criteria_id'
    ];

    public function scraperCriteria()
    {
        return $this->belongsTo(ScraperCriteria::class);
    }

    public function googleBusinesses()
    {
        return $this->hasMany(GoogleBusiness::class);
    }

}
