<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'member_id',
        'title',
        'message',
        'type',
        'is_read',
        'reference_id',
        'reference_type',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
