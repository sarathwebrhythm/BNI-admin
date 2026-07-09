<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferStat extends Model
{
    protected $fillable = [
        'offer_id',
        'member_id',
        'type',
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}