<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DecisionMaker extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'google_business_id',
        'url',
        'validate'
    ];

    public function googleBusiness()
    {
        return $this->belongsTo(GoogleBusiness::class);
    }

    public function decisionMakerEmails()
    {
        return $this->hasMany(DecisionMakersEmails::class);
    }
}
