<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
         'offer_limit',
        'status',
    ];
    public function members()
    {
        return $this->hasMany(Member::class);
    }
}