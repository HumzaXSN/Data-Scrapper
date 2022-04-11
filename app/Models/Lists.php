<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Lists extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'list_type_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function listType()
    {
        return $this->belongsTo(ListType::class);
    }

    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'contact_lists', 'list_id', 'contact_id');
    }
}
