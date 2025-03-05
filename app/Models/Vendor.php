<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical\Distributions\F;
use Tymon\JWTAuth\Contracts\JWTSubject;


class Vendor extends Authenticatable implements JWTSubject
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'username',
        'password',
        'national_id',
        'status',
        'image',
        'otp',
        'otp_expire_at',
    ];
    protected $casts = [];



    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    public function getJWTCustomClaims()
    {
        return [];
    }

 public function vendor_modules()
    {
        return $this->hasMany(VendorModule::class);
    }

//    public function restaurant()
//    {
//        return $this->hasMany(Restaurant::class , "vendor_id");
//    }

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }



}
