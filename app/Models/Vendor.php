<?php

namespace App\Models;

use App\Traits\AutoFillable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;


class Vendor extends Authenticatable implements JWTSubject
{
use AutoFillable;
use HasRoles,LogsActivity;


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


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }


}
