<?php

namespace App\Models;

use App\Traits\AutoFillable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class Vendor extends Authenticatable implements JWTSubject
{
use AutoFillable;

    protected $fillable = [];
    protected $casts = [];



    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    public function getJWTCustomClaims()
    {
        return [];
    }





}
