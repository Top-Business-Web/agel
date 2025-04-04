<?php

namespace App\Services\Admin;

use App\Mail\Otp;
use App\Models\Admin;
use App\Models\Region;
use Illuminate\Http\JsonResponse;
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
        } else {
            return view('admin.auth.login');

        }
    }

    public function login($request): JsonResponse
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
            $admin = Admin::where('phone', '+966'.$data['input'])->first();

            if (!$admin) {
                return response()->json([
                    'status' => 208,
                ], 206);
            }
            if ($admin->status == 0) {
                return response()->json([
                    'status' => 207,
                ], 207);
            }
            $credentials = [
                'phone' => '+966'.$data['input'],
                'password' => $data['password'],
            ];
            if (Auth::guard('admin')->attempt($credentials)) {
                return response()->json([
                    'status' => 204,
                    'email' => $admin->email
                ], 200);
            } else {
                return response()->json([
                    'status' => 205,
                    'email' => $admin->email
                ], 205);
            }
        } elseif ($request->verificationType == 'email') {
            $admin = Admin::where('email', $data['input'])->first();
            $credentials = [
                'email' => $data['input'],
                'password' => $data['password'],
            ];
            if (!$admin) {
                return response()->json([
                    'status' => 206,
//                    'email' => $admin->email
                ], 206);
            }
            if ($admin->status == 0) {
                return response()->json([
                    'status' => 207,
                ], 207);
            }

            if (Auth::guard('admin')->validate($credentials)) {
                $otp = rand(1000, 9999);
                $admin->update([
                    'otp' => $otp,
                    'otp_expire_at' => now()->addMinutes(5)
                ]);

                try{
                    Mail::to($admin->email)->send(new Otp($admin->name, $otp));
                }catch(\Exeption $e){

                }


                return response()->json([
                    'status' => 200,
                    'email' => $admin->email
                ], 200);
            } else {
                return response()->json([
                    'status' => 208,
                    'email' => $admin->email
                ], 208);
            }
        }

        return response()->json([
            'status' => 205,
        ], 205);
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


    public function register($request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admins,email',
            'phone' => 'required|numeric|unique:admins,phone',
            'password' => 'required|min:6|confirmed',
            'region_id' => 'required|exists:regions,id',
            'commercial_number' => 'required|unique:admins,commercial_number',
            'national_id' => 'required|numeric|unique:admins,national_id',
        ]);

        $admin = Admin::create([
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

        if ($admin) {
            // generate otp
            $otp = rand(1000, 9999);
            $admin->update([
                'otp' => $otp,
                'otp_expire_at' => now()->addMinutes(5)
            ]);

            Mail::to($admin->email)->send(new Otp($admin->name, $otp));
            return response()->json([
                'status' => 200,
                'email' => $admin->email
            ], 200);
        }

        return response()->json([
            'status' => 405,
            'message' => 'لم يتم العثور على المكتب'
        ], 405);

    }

    public function generateUsername($name)
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
        return view('admin.auth.verify-otp', ['email' => $email, 'type' => $type, 'resetPassword' => $resetPassword]);

    }



    public
    function verifyOtp($request)
    {
        $admin = Admin::where('email', $request->email)->first();

        if ($admin && $admin->otp == $request->otp && $admin->otp_expire_at > now()) {
            $admin->update([
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
                    'email' => $admin->email,
                    'message' => 'لم يتم العثور على المكتب'
                ], 200);
            } else {
                Auth::guard('admin')->login($admin);
                return response()->json(200);
            }
        }
        if ($admin && $admin->otp == $admin->otp && $admin->otp_expire_at < now()) {
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
        return view('admin.auth.verify-reset-password');
    }

    public function newPasswordForm($email)
    {
        return view('admin.auth.new-password', ['email' => $email]);
    }

    public function resetPassword($request)
    {
        $request->validate([
            'email'=>'required|exists:admins,email',
            'password' => 'required|min:6|confirmed',
        ]);
        $admin = Admin::where('email', $request->email)->first();
        $admin->update([
            'password'=>Hash::make($request->password)
        ]);
        Auth::guard('admin')->login($admin);
        return response()->json([
            'status' => 200,
            'email' => $admin->email,
            'message' => 'لم يتم العثور على المكتب'
        ], 200);
    }

    public function verifyResetPassword($request): \Illuminate\Http\JsonResponse
    {
        $admin = Admin::where('email', $request->input)->first();

        if ($admin) {
            // generate otp
            $otp = rand(1000, 9999);
            $admin->update([
                'otp' => $otp,
                'otp_expire_at' => now()->addMinutes(5)
            ]);

            Mail::to($admin->email)->send(new Otp($admin->name, $otp));
            return response()->json([
                'status' => 209,
                'email' => $admin->email
            ], 200);
        }

        return response()->json([
            'status' => 405,
            'message' => 'لم يتم العثور على المكتب'
        ], 200);

    }



    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
