<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Vendor\OrderController;
use App\Http\Controllers\Vendor\StockController;

use App\Http\Controllers\Vendor\AuthController;
use App\Http\Controllers\Vendor\HomeController;
use App\Http\Controllers\Vendor\PlanController;
use App\Http\Controllers\Vendor\RoleController;
use App\Http\Controllers\Vendor\BranchController;
use App\Http\Controllers\Vendor\ClientController;
use App\Http\Controllers\Vendor\VendorController;
use App\Http\Controllers\Vendor\SettingController;
use App\Http\Controllers\Vendor\CategoryController;
use App\Http\Controllers\Vendor\InvestorController;
use App\Http\Controllers\Vendor\ActivityLogController;
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
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
    ],
    function () {
        Route::get('/partner', [AuthController::class, 'index'])->name('vendor.login');
        Route::get('/register', [AuthController::class, 'registerForm'])->name('vendor.register');

        Route::group(['prefix' => 'vendor'], function () {
            Route::POST('/login', [AuthController::class, 'login'])->name('vendor.login');
            Route::POST('/register', [AuthController::class, 'register'])->name('vendor.register');
            Route::get('/verify-otp/{email}/{type}/{resetPassword}', [AuthController::class, 'showOtpForm'])->name('vendor.otp.verify');
            Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('vendor.otp.check');
            Route::POST('/verify-reset-password', [AuthController::class, 'verifyResetPassword'])->name('vendor.verifyResetPassword');
            Route::get('/reset-password', [AuthController::class, 'resetPasswordForm'])->name('vendor.resetPasswordForm');
            Route::get('/new-password/{email}', [AuthController::class, 'newPasswordForm'])->name('vendor.newPasswordForm');
            Route::POST('/reset-password/{email}', [AuthController::class, 'ResetPassword'])->name('vendor.resetPassword');

            Route::get('my_profile', [VendorController::class, 'myProfile'])->name('vendor.myProfile');

            Route::group(['middleware' => 'auth:vendor'], function () {
                #============================ Home ====================================
                Route::get('homeVendor', [HomeController::class, 'index'])->name('vendorHome');
                #============================ branches ==================================
                Route::resourceWithDeleteSelected('branches', BranchController::class);
                #============================ categories ==================================
                Route::resourceWithDeleteSelected('categories', CategoryController::class);
                #============================ vendors ====================================

                Route::get('vendors/index', [VendorController::class, 'index'])->name('vendor.vendors.index');
                Route::get('vendors/create', [VendorController::class, 'create'])->name('vendor.vendors.create');
                Route::post('vendors', [VendorController::class, 'store'])->name('vendor.vendors.store');
                Route::put('vendors/update', [VendorController::class, 'update'])->name('vendor.vendors.update');
                Route::delete('vendors/{id}', [VendorController::class, 'destroy'])->name('vendor.vendors.destroy');
                Route::get('vendors/{id}/edit', [VendorController::class, 'edit'])->name('vendor.vendors.edit');
                Route::post('vendors/delete-selected', [VendorController::class, 'deleteSelected'])->name('vendor.vendors.deleteSelected');
                Route::post('vendors/update-column-selected', [VendorController::class, 'updateColumnSelected'])->name('vendor.vendors.updateColumnSelected');

                #============================ logout ====================================
                Route::get('logout', [AuthController::class, 'logout'])->name('vendor.logout');
                #============================ roles and permissions ====================================
                Route::resourceWithDeleteSelected('roles', RoleController::class, [
                    'as' => 'vendor',
                ]);
                Route::post('roles/delete-selected', [RoleController::class, 'deleteSelected'])->name('vendor.roles.deleteSelected');

                Route::get('activity_logs', [ActivityLogController::class, 'index'])->name('vendor.activity_logs.index');
                Route::delete('activity_logs/{id}', [ActivityLogController::class, 'destroy'])->name('vendor.activity_logs.destroy');
                Route::post('activity_logs/delete-selected', [ActivityLogController::class, 'deleteSelected'])->name('vendor.activity_logs.deleteSelected');

                //============================ VendorSetting ====================================

                Route::get('vendor/setting', [SettingController::class, 'index'])->name('vendorSetting');
                Route::post('vendor/setting/update', [SettingController::class, 'update'])->name('vendorSetting.store');
                #============================ investors ====================================

                Route::resourceWithDeleteSelected('investors', InvestorController::class);
                Route::get('investors/add-stock/{id}', [InvestorController::class, 'addStockForm'])->name('vendor.investors.addStock');
                Route::post('investors/store-stock', [InvestorController::class, 'storeStock'])->name('vendor.investors.storeStock');
                Route::get('/getAvailableStock', [InvestorController::class, 'getAvailableStock'])->name('vendor.investors.getAvailableStock');

                #============================ client ====================================
                Route::resourceWithDeleteSelected('clients', ClientController::class);
                Route::get('/get-user-by-national-id', [ClientController::class, 'getUserByNationalId'])->name('vendor.clients.getUserByNationalId'); //using for order

                #============================ Stocks ==================================
                Route::resourceWithDeleteSelected('stocks', StockController::class);

                #============================ plans ==================================

                Route::resourceWithDeleteSelected('plans', PlanController::class, [
                    'as' => 'vendor',
                ]);

                #============================ orders ==================================
                Route::resourceWithDeleteSelected('orders', OrderController::class);
                Route::get('/get-prices', [OrderController::class, 'calculatePrices'])->name('vendor.orders.calculatePrices'); //using for order
            });


            Route::resourceWithDeleteSelected('unsurpasseds', \App\Http\Controllers\Vendor\UnsurpassedController::class);
            Route::get('unsurpasseds/add/Excel',[ \App\Http\Controllers\Vendor\UnsurpassedController::class,'addExcel'])->name('unsurpasseds.add.excel');
            Route::post('unsurpasseds/store/Excel',[ \App\Http\Controllers\Vendor\UnsurpassedController::class,'storeExcel'])->name('unsurpasseds.store.excel');

        });

        #=======================================================================
        #============================ ROOT =====================================
        #=======================================================================

        Route::get('/check-vendor-limit/{key}', function ($key) {
            return response()->json([
                'allowed' => checkVendorPlanLimit($key),
            ]);
        });

        Route::get('/clear', function () {
            Artisan::call('cache:clear');
            Artisan::call('key:generate');
            Artisan::call('config:clear');
            Artisan::call('optimize:clear');
            return response()->json(['status' => 'success', 'code' => 1000000000]);
        });
    },
);
