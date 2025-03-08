<?php

use App\Http\Controllers\Vendor\AuthController;

use App\Http\Controllers\Vendor\HomeController;
use App\Http\Controllers\Vendor\RoleController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


/*
|--------------------------------------------------------------------------
| Vendor Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "Vendor" middleware group. Now create something great!
|
*/

Route::group(
    [

        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {

        Route::get('/partner', [AuthController::class, 'index'])->name('vendor.login');
        Route::get('/register', [AuthController::class, 'registerForm'])->name('vendor.register');

        Route::group(['prefix' => 'vendor'], function () {
            Route::POST('login', [AuthController::class, 'login'])->name('vendor.login');
            Route::POST('/register', [AuthController::class, 'register'])->name('vendor.register');
            Route::get('/verify-otp/{email}', [AuthController::class, 'showOtpForm'])->name('otp.verify');
            Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('otp.check');


            Route::group(['middleware' => 'auth:vendor'], function () {



                #============================ Home ====================================
                Route::get('homeVendor', [HomeController::class, 'index'])->name('vendorHome');

                #============================ logout ====================================
                Route::get('logout', [AuthController::class, 'logout'])->name('vendor.logout');
                #============================ roles and permissions ====================================
                Route::resourceWithDeleteSelected('roles', RoleController::class, [
                    'as' => 'vendor'  // Prefix "vendor." to all route names
                ]);
            });
        });

        #=======================================================================
        #============================ ROOT =====================================
        #=======================================================================
        Route::get('/clear', function () {

            Artisan::call('cache:clear');
            Artisan::call('key:generate');
            Artisan::call('config:clear');
            Artisan::call('optimize:clear');
            return response()->json(['status' => 'success', 'code' => 1000000000]);
        });
    }
);
