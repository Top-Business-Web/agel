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
    use HasRoles, LogsActivity;


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


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll();
    }


//    public function getActivitylogOptions(): LogOptions
//    {
//        $guardName = $this->getCurrentGuardName(); // Use the helper function to get the current guard
//
//        // Log activity only if the user is a vendor
//        if ($guardName === 'vendor') {
//            return LogOptions::defaults()
//                ->logAll();  // Log all changes for vendor
//        }
//
//        // If not a vendor, return empty or no logging (depending on your requirement)
//        return LogOptions::defaults();
//    }
//
//    function getCurrentGuardName()
//    {
//        // Loop through each guard in the config file
//        foreach (config('auth.guards') as $guard => $config) {
//            // Check if the user is authenticated using this guard
//            if (Auth::guard($guard)->check()) {
//                return $guard;  // Return the guard name
//            }
//        }
//
//        // Return null if no guard is authenticated
//        return null;
//    }
}
