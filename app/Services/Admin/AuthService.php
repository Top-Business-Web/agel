<?php

namespace App\Services\Admin;

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use App\Services\BaseService;

class AuthService
{
    public function index()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('adminHome');
        }
        return view('admin.auth.login');
    }

    public function login( $request): \Illuminate\Http\JsonResponse
    {
        // التحقق من البيانات المدخلة
        $data = $request->validate(
            [
                'input' => 'required',
                'password' => 'required',
            ],
            [
                'input.required' => 'يرجى إدخال اسم المستخدم أو البريد الإلكتروني أو الكود',
                'password.required' => 'يرجي ادخال كلمة المرور',
            ]
        );

        $admin = Admin::where('user_name', $data['input'])
            ->orWhere('email', $data['input'])
            ->orWhere('code', $data['input'])
            ->first();

        if (!$admin) {
            return response()->json(['message' => 'بيانات تسجيل الدخول غير صحيحة'], 401);
        }

        $credentials = [
            (filter_var($data['input'], FILTER_VALIDATE_EMAIL) ? 'email' : (is_numeric($data['input']) ? 'code' : 'user_name')) => $data['input'],
            'password' => $data['password'],
        ];

        if (Auth::guard('admin')->attempt($credentials)) {
            return response()->json(200);
        }
        return response()->json(405);

    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        toastr()->info('تم تسجيل الخروج');
        return redirect()->route('admin.login');
    }
}
