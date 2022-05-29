<?php

namespace App\Models;

use App\Models\ScraperJob;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ScraperCriteria extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'status',
        'keyword',
        'location',
        'limit'
    ];

    public function scraperJobs()
    {
        return $this->hasMany(ScraperJob::class);
    }

    public function googlebusinesses()
    {
        return $this->hasManyThrough(GoogleBusiness::class, ScraperJob::class);
    }
}
