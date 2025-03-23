<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Illuminate\Support\Facades\Auth;

class vendor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('vendor')->check()){
            if ($request=='login'){
                return redirect()->route('vendorHome');
            }
            return $next($request);
        }
        return redirect()->route('vendor.login');
    }
}
