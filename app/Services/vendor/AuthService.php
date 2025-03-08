<?php

namespace App\Services\vendor;

use App\Mail\Otp;
use App\Models\City;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthService
{
    public function index($key = null)
    {
        if (Auth::guard('vendor')->check() && Auth::guard('vendor')->user()->status == 1) {
            return redirect()->route('vendorHome');
        }
        if ($key == 'login') {

            return view('vendor.auth.login');
        }else{
            $cites = City::select('id', 'name')->where('status', 1)->get();
            return view('vendor.auth.register', compact('cites'));
        }
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


    public function register($request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:vendors,email',
            'phone' => 'required|numeric|unique:vendors,phone',
            'password' => 'required|min:6|confirmed',
            'city_id' => 'required|exists:cities,id',
            'commercial_number' => 'required|unique:vendors,commercial_number',
            'national_id' => 'required|numeric|unique:vendors,national_id',
        ]);

        $vendor = Vendor::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'city_id' => $request->city_id,
            'commercial_number' => $request->commercial_number,
            'national_id' => $request->national_id,
            'username' => $this->generateUsername($request->name),
            'status' => 0,
            'plan_id' => 1

        ]);

        if ($vendor) {
            // genrate otp
            $otp = rand(1000, 9999);
            $vendor->update([
                'otp' => $otp,
            'otp_expire_at' => now()->addMinutes(5)
            ]);

        Mail::to($vendor->email)->send(new Otp($vendor->name, $otp));
            return response()->json([
                'status' => 200,
                'email' => $vendor->email
            ], 200);
        }

        return response()->json([
            'status' => 405,
            'message' => 'لم يتم العثور على المكتب'
        ], 405);

    }




    public function generateUsername($name)
    {
        return str_replace(' ', '', strtolower($name)).rand(1000,9999);


    }
    public function showOtpForm($email)
    {
        return view('vendor.auth.verify-otp', ['email' => $email]);
    }



    public function verifyOtp($request)
    {
        $vendor = Vendor::where('email', $request->email)->first();

        if ($vendor && $vendor->otp == $request->otp&& $vendor->otp_expire_at > now()) {
            $vendor->update([
                'otp' => null,
                'otp_expire_at' => null,
                'status' => 1
            ]);
             Auth::guard('vendor')->login($vendor);
            return  response()->json(200);
        }else{

            return  response()->json(200);
        }

    }

    public function logout()
    {
        Auth::guard('vendor')->logout();
        toastr()->info(trns('تم تسجيل الخروج'));
        return redirect('/partner');
    }
}
