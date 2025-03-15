<?php

namespace App\Http\Controllers\Admin;

use App\Services\Admin\AuthService as ObjService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    public function __construct(protected ObjService $objService){

    }

    public function index()
    {
        return $this->objService->index();
    }

    public function login(Request $request)
    {
        return $this->objService->login($request);
    }
    public function registerForm()
    {
        return $this->objService->index();
    }
    public function register(Request $request)
    {
        return $this->objService->register($request);
    }
    public function showOtpForm($email,$type)
    {
        return $this->objService->showOtpForm($email,$type);

    }


    public function verifyOtp(Request $request)
    {
        return $this->objService->verifyOtp($request);

    }


    public function logout()
    {
        return $this->objService->logout();
    }

}//end class
