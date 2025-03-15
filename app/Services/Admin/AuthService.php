<?php

namespace App\Services\Admin;

use App\Mail\Otp;
use App\Models\Admin;
use App\Models\Region;
use Illuminate\Support\Facades\Auth;
use App\Services\BaseService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthService extends BaseService
{
//    public function index()
//    {
//        if (Auth::guard('admin')->check()) {
//            return redirect()->route('adminHome');
//        }
//        return view('admin.auth.login');
//    }
    public function __construct(Admin $model, protected Region $region)
    {
        parent::__construct($model);
    }
    public function index($key = null)
    {
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->status == 1) {
            return redirect()->route('adminHome');
        }else{
            return view('admin.auth.login');

        }
//        if ($key == 'login') {
//
//            return view('admin.auth.login');
//        }
//        } else {
////            $cites = City::select('id', 'name')->where('status', 1)->get();
//            $regions = $this->region->get();
//            return view('admin.auth.register', compact('regions'));
//        }
    }

    public function login($request): \Illuminate\Http\JsonResponse
    {
//dd($request);
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
//            dd($data);
            $vendor = Admin::where('phone', $data['input'])->first();

            if ($vendor==null) {
                return response()->json([
                    'status' => 205,
//                    'email' => $vendor->email
                ], 200);
            }
            if ($vendor->status == 0) {
                return response()->json([
                    'status' => 207,
//                    'email' => $vendor->email
                ], 200);
            }
            $credentials = [
                'phone' => $data['input'],
                'password' => $data['password'],
            ];
            if (Auth::guard('admin')->attempt($credentials)) {
                return response()->json([
                    'status' => 204,
                    'email' => $vendor->email
                ], 200);
            } else {
                return response()->json([
                    'status' => 205,
                    'email' => $vendor->email
                ], 200);
            }
        } elseif ($request->verificationType == 'email') {
            $vendor = Admin::where('email', $data['input'])->first();
            $credentials = [
                'email' => $data['input'],
                'password' => $data['password'],
            ];
            if (!$vendor) {
                return response()->json([
                    'status' => 206,
//                    'email' => $vendor->email
                ], 200);
            }
            if ($vendor->status == 0) {
                return response()->json(206);
            }

            if (Auth::guard('admin')->validate($credentials)) {
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
        }

        return response()->json([
            'status' => 405,
            'message' => 'لم يتم العثور على المشرف'
        ], 405);
    }



//    public function login( $request): \Illuminate\Http\JsonResponse
//    {
//        // التحقق من البيانات المدخلة
//        $data = $request->validate(
//            [
//                'input' => 'required',
//                'password' => 'required',
//            ],
//            [
//                'input.required' => 'يرجى إدخال اسم المستخدم أو البريد الإلكتروني أو الكود',
//                'password.required' => 'يرجي ادخال كلمة المرور',
//            ]
//        );
//
//        $admin = Admin::where('user_name', $data['input'])
//            ->orWhere('email', $data['input'])
//            ->orWhere('code', $data['input'])
//            ->first();
//
//        if (!$admin) {
//            return response()->json(['message' => 'بيانات تسجيل الدخول غير صحيحة'], 401);
//        }
//
//        $credentials = [
//            (filter_var($data['input'], FILTER_VALIDATE_EMAIL) ? 'email' : (is_numeric($data['input']) ? 'code' : 'user_name')) => $data['input'],
//            'password' => $data['password'],
//        ];
//
//        if (Auth::guard('admin')->attempt($credentials)) {
//            return response()->json(200);
//        }
//        return response()->json(405);
//
//    }


    public
    function register($request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:vendors,email',
            'phone' => 'required|numeric|unique:vendors,phone',
            'password' => 'required|min:6|confirmed',
            'region_id' => 'required|exists:regions,id',
            'commercial_number' => 'required|unique:vendors,commercial_number',
            'national_id' => 'required|numeric|unique:vendors,national_id',
        ]);

        $vendor = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'region_id' => $request->region_id,
            'commercial_number' => $request->commercial_number,
            'national_id' => $request->national_id,
            'username' => $this->generateUsername($request->name),
            'status' => 0,
            'plan_id' => 1

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
    function showOtpForm($email, $type)
    {

        return view('admin.auth.verify-otp', ['email' => $email, 'type' => $type]);
    }


    public
    function verifyOtp($request)
    {
        $vendor = Admin::where('email', $request->email)->first();

        if ($vendor && $vendor->otp == $request->otp && $vendor->otp_expire_at > now()) {
            $vendor->update([
                'otp' => null,
                'otp_expire_at' => null,
                'status' => 1
            ]);
            Auth::guard('vendor')->login($vendor);
            return response()->json(200);
        } else {

            return response()->json(500);
        }

    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        toastr()->info('تم تسجيل الخروج');
        return redirect()->route('admin.login');
    }
}
