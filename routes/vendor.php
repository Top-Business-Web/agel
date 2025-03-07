<?php

use App\Http\Controllers\Vendor\AuthController;

use App\Http\Controllers\Vendor\HomeController;
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

        Route::group(['prefix' => 'vendor'], function () {
            Route::POST('login', [AuthController::class, 'login'])->name('vendor.login');

            Route::group(['middleware' => 'auth:vendor'], function () {



                #============================ Home ====================================
                Route::get('homeVendor', [HomeController::class, 'index'])->name('vendorHome');

                #============================ logout ====================================
                Route::get('logout', [AuthController::class, 'logout'])->name('vendor.logout');
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
