<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;


class Member extends Authenticatable implements JWTSubject

{
    use HasFactory;

    protected $fillable = [
        'bni_id',
        'name',
        'email',
        'phone',
        'password',
        'company',
         'profile_photo',
         'joining_date',
        'chapter',
        'designation',
        'status',
    ];
    protected $hidden = [
        'password',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($member) {

            do {
                $bniId = 'BNI-TVM-' . str_pad(
                    random_int(0, 999999),
                    6,
                    '0',
                    STR_PAD_LEFT
                );
            } while (self::where('bni_id', $bniId)->exists());

            $member->bni_id = $bniId;
        });
    }
    public function getJWTIdentifier()
{
    return $this->getKey();
}

public function getJWTCustomClaims()
{
    return [];
}
}
