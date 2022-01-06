<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'description'
    ];

    public function contacts()
    {
        return $this->belongsToMany(Contact::class);
    }

    public function google_businesses()
    {
        return $this->belongsToMany(GoogleBusiness::class);
    }
}
