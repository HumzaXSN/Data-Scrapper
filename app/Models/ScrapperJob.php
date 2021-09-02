<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScrapperJob extends Model
{
    use HasFactory;
    protected $table = 'scrapper_jobs';
    protected $fillable = [
        'ip',
        'url',
        'platform',
        'status',
    ];

}
