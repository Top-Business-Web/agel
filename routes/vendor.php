<?php

use App\Http\Controllers\Vendor\ActivityLogController;
use App\Http\Controllers\Vendor\AuthController;

use App\Http\Controllers\Vendor\HomeController;
use App\Http\Controllers\Vendor\RoleController;
use App\Http\Controllers\Vendor\VendorController;
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
                #============================ vendors ====================================
//                    Route::resourceWithDeleteSelected('vendors', VendorController::class);
                Route::get('vendors/index', [VendorController::class, 'index'])->name('vendor.vendors.index');
                Route::get('vendors/create', [VendorController::class, 'create'])->name('vendor.vendors.create');
                Route::post('vendors', [VendorController::class, 'store'])->name('vendor.vendors.store');                Route::put('vendors/update', [VendorController::class, 'update'])->name('vendor.vendors.update');
                Route::delete('vendors/{id}', [VendorController::class, 'destroy'])->name('vendor.vendors.destroy');
                Route::get('vendors/{id}/edit', [VendorController::class, 'edit'])->name('vendor.vendors.edit');
                Route::get('vendors/delete-selected', [VendorController::class, 'deleteSelected'])->name('vendor.vendors.deleteSelected');
                Route::post('vendors/update-column-selected', [VendorController::class, 'updateColumnSelected'])->name('vendor.vendors.updateColumnSelected');
                #============================ logout ====================================
                Route::get('logout', [AuthController::class, 'logout'])->name('vendor.logout');
                #============================ roles and permissions ====================================
                Route::resourceWithDeleteSelected('roles', RoleController::class, [
                    'as' => 'vendor'  // Prefix "vendor." to all route names
                ]);
                Route::post('roles/delete-selected', [RoleController::class, 'deleteSelected'])->name('vendor.roles.deleteSelected');

                Route::get('activity_logs', [ActivityLogController::class, 'index'])->name('vendor.activity_logs.index');
                Route::delete('activity_logs/{id}', [ActivityLogController::class, 'destroy'])->name('vendor.activity_logs.destroy');
                Route::post('activity_logs/delete-selected', [ActivityLogController::class, 'deleteSelected'])->name('vendor.activity_logs.deleteSelected');

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
