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
    use HasRoles;


    protected $fillable = [];
    protected $casts = [];

    public function branches()
    {
        return $this->hasMany(VendorBranch::class);

    }


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    public function getJWTCustomClaims()
    {
        return [];
    }


    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'userable');
    }

}
