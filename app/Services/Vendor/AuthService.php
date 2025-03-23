<?php

namespace App\Services\Vendor;

use App\Mail\Otp;
use App\Models\Branch;
use App\Models\Region;
use App\Models\Vendor;
use App\Models\VendorBranch;
use App\Services\BaseService;
use App\Services\Vendor\AuthService as ObjService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthService extends BaseService
{
    public function __construct(Vendor $model, protected Region $region)
    {
        parent::__construct($model);
    }

    public function index($key = null)
    {
        if (Auth::guard('vendor')->check() && Auth::guard('vendor')->user()->status == 1) {
            return redirect()->route('vendorHome');
        }
        if ($key == 'login') {

            return view('vendor.auth.login');
        } else {
//            $cites = City::select('id', 'name')->where('status', 1)->get();
            $regions = $this->region->get();
            return view('vendor.auth.register', compact('regions'));
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
        if ($request->verificationType == 'phone') {
//            dd($request->all());
            $vendor = Vendor::where('phone', $data['input'])->first();
            if (!$vendor) {
                return response()->json([
                    'status' => 205,
//                    'email' => $vendor->email
                ], 200);
            }
            if ($vendor->status == 0) {
                return response()->json([
                    'status' => 205,
//                    'email' => $vendor->email
                ], 200);
            }
            $credentials = [
                'phone' => $data['input'],
                'password' => $data['password'],
            ];
            if (Auth::guard('vendor')->attempt($credentials)) {
                return response()->json([
                    'status' => 204,
                    'email' => $vendor->email
                ], 200);
            } else {
                return response()->json([
                    'status' => 207,
                    'email' => $vendor->email
                ], 200);
            }
        } elseif ($request->verificationType == 'email') {

            $vendor = Vendor::where('email', $data['input'])->first();
            $credentials = [
                'email' => $data['input'],
                'password' => $data['password'],
            ];
//            dd($credentials);
            if (!$vendor) {
                return response()->json([
                    'status' => 206,
//                    'email' => $vendor->email
                ], 200);
            }
            if ($vendor->status == 0) {
                return response()->json(206);
            }
//            dd($credentials,$vendor);

            if (Auth::guard('vendor')->validate($credentials)) {
//                dd('dsfsdf');
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

            } else {
                return response()->json([
                    'status' => 207,
                    'email' => $vendor->email
                ], 200);
            }
        }

        return response()->json([
            'status' => 405,
            'message' => 'لم يتم العثور على المكتب'
        ], 405);
    }


    public
    function register($request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:vendors,email',
            'phone' => 'required|numeric|digits:9|unique:vendors,phone',
            'password' => 'required|min:6|confirmed',
            'region_id' => 'required|exists:regions,id',
            'commercial_number' => 'required|digits:10|numeric|unique:vendors,commercial_number',
            'national_id' => 'required|numeric|digits:10|unique:vendors,national_id',
        ]);

        $vendor = Vendor::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => '+966' . $request->phone,
            'password' => Hash::make($request->password),
            'region_id' => $request->region_id,
            'commercial_number' => $request->commercial_number,
            'national_id' => $request->national_id,
            'username' => $this->generateUsername($request->name),
            'status' => 0,
            'plan_id' => 1

        ]);

// Create primary branch for the vendor with default settings
        $branch = Branch::create([
            'vendor_id' => $vendor->id,
            'region_id' => $vendor->region_id,
            'status' => 1,
            'is_main' => 1,
            'name' => 'الفرع الرئيسي'
        ]);

// Associate vendor with the created branch
        $vendorBranch = VendorBranch::create([
            'vendor_id' => $vendor->id,
            'branch_id' => $branch->id,
        ]);

        if ($vendor) {
            // generate otp
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


    public
    function generateUsername($name)
    {
        return str_replace(' ', '', strtolower($name)) . rand(1000, 9999);


    }

    public
    function showOtpForm($email, $type, $resetPassword)
    {

//        if ($resetPassword == 2) {
////            return view('vendor.auth.reset-password', ['email' => $email, 'type' => $type, 'resetPassword' => $resetPassword]);
////            return view('vendor.auth.reset-password', ['email' => $email, 'type' => $type, 'resetPassword' => $resetPassword]);
//        }
        if ($resetPassword == null) {
            $resetPassword = 1;
        }
        return view('vendor.auth.verify-otp', ['email' => $email, 'type' => $type, 'resetPassword' => $resetPassword]);

    }


    public
    function verifyOtp($request)
    {
//        dd($request);
        $vendor = Vendor::where('email', $request->email)->first();

        if ($vendor && $vendor->otp == $request->otp && $vendor->otp_expire_at > now()) {
            $vendor->update([
                'otp' => null,
                'otp_expire_at' => null,
                'status' => 1
            ]);
            if ($request->isReset == 2) {
//                dd('sl/kd/fjl');
//                return  redirect()->route('vendor.newPasswordForm',['email' => $request->email]);
//                return redirect('', ['email' => $request->email]);

                return response()->json([
                    'status' => 300,
                    'email' => $vendor->email,
                    'message' => 'لم يتم العثور على المكتب'
                ], 200);
            } else {
                Auth::guard('vendor')->login($vendor);
                return response()->json(200);
            }
        }
        if ($vendor && $vendor->otp == $vendor->otp && $vendor->otp_expire_at < now()) {
            return response()->json([
                'status' => 400,
                'message' => 'إنتهت صلاحية هذا الكود'
            ], 200);
        } else {

            return response()->json(500);
        }

    }

    public function resetPasswordForm()
    {
        return view('vendor.auth.verify-reset-password');
    }

    public function newPasswordForm($email)
    {
        return view('vendor.auth.new-password', ['email' => $email]);
    }

    public function resetPassword($request)
    {
        $request->validate([
            'email'=>'required|exists:vendors,email',
            'password' => 'required|min:6|confirmed',
        ]);
        $vendor = Vendor::where('email', $request->email)->first();
        $vendor->update([
            'password'=>Hash::make($request->password)
        ]);
        Auth::guard('vendor')->login($vendor);
        return response()->json([
            'status' => 200,
            'email' => $vendor->email,
            'message' => 'لم يتم العثور على المكتب'
        ], 200);
    }

    public function verifyResetPassword($request): \Illuminate\Http\JsonResponse
    {
//        dd($request->all());
        $vendor = Vendor::where('email', $request->input)->first();

        if ($vendor) {
            // generate otp
            $otp = rand(1000, 9999);
            $vendor->update([
                'otp' => $otp,
                'otp_expire_at' => now()->addMinutes(5)
            ]);

            Mail::to($vendor->email)->send(new Otp($vendor->name, $otp));
            return response()->json([
                'status' => 209,
                'email' => $vendor->email
            ], 200);
        }

        return response()->json([
            'status' => 405,
            'message' => 'لم يتم العثور على المكتب'
        ], 200);

    }

    public
    function logout()
    {
        Auth::guard('vendor')->logout();
        toastr()->info('تم تسجيل الخروج');
        return redirect('/partner');
    }
}
