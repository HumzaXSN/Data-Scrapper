<?php

namespace App\Models;

use App\Models\Note;
use App\Models\Industry;
use App\Models\LeadStatus;
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
        'country',
        'city',
        'state',
        'linkedin_profile',
        'reached_count',
        'reached_platform',
        'lead_status_id',
        'industry_id',
    ];

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }

    public function lead_status()
    {
        return $this->belongsTo(LeadStatus::class);
    }

    public function Notes()
    {
        return $this->belongsToMany(Note::class);
    }
}
