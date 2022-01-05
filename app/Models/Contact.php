<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'title',
        'company',
        'phone',
        'email',
        'source',
        'status',
        'city',
        'state',
        'linkedin_profile',
        'times_reached',
        'reached_platform',
        'lead_status',
        'industry_id'
    ];

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }
}
