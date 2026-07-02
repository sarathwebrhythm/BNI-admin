<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'offer_category_id',
        'title',
        'discount',
        'description',
        'start_date',
        'end_date',
        'terms',
        'image',
        'contact_number',
        'views',
        'redemptions',
        'saves',
        'status',
        'order',
    ];

    protected $casts = [
        'terms' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function category()
    {
        return $this->belongsTo(OfferCategory::class, 'offer_category_id');
    }
    public function stats()
    {
        return $this->hasMany(OfferStat::class);
    }

    public function views()
    {
        return $this->hasMany(OfferStat::class)->where('type', 'view');
    }

    public function redemptions()
    {
        return $this->hasMany(OfferStat::class)->where('type', 'redemption');
    }

    public function saves()
    {
        return $this->hasMany(OfferStat::class)->where('type', 'saved');
    }
}
