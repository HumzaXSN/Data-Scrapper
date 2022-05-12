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
        'location'
    ];

    public function scraperJobs() {
        return $this->hasMany(ScraperJob::class);
    }
}
