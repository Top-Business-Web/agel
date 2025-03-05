<?php

namespace App\Services\vendor;

use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function index()
    {
        if (Auth::guard('vendor')->check() && Auth::guard('vendor')->user()->status == 1) {
            return redirect()->route('vendorHome');
        }
        return view('vendor.auth.login');
    }

    public function login($request): \Illuminate\Http\JsonResponse
    {

        $data = $request->validate(
            [
                'input' => 'required',
                'password' => 'required',

            ],
            [

                'password.required' => 'يرجي ادخال كلمة المرور',
            ]
        );

        $vendor = Vendor::where('username', $data['input'])->first();
        if($vendor->status == 0){
            return response()->json(405);
        }
        $credentials = [];
        if ($vendor) {
            $credentials['username'] = $data['input'];
        } else {
            $credentials['email'] = $data['input'];
        }
        $credentials['password'] = $data['password'];



        if (Auth::guard('vendor')->attempt($credentials)) {
            return response()->json(200);
        }
        return response()->json(405);
    }

    public function logout()
    {
        Auth::guard('vendor')->logout();
        toastr()->info(trns('تم تسجيل الخروج'));
        return redirect('/partner');
    }
}
