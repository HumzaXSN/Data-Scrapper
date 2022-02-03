<?php

namespace App\Models;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeadStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}
