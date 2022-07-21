<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DecisionMakersEmails extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'email',
        'decision_maker_id',
    ];

    public function decisionMaker()
    {
        return $this->belongsTo(DecisionMaker::class);
    }
}
