<?php

use App\Http\Controllers\Vendor\ActivityLogController;
use App\Http\Controllers\Vendor\AuthController;
use App\Http\Controllers\Vendor\BranchController;
use App\Http\Controllers\Vendor\CategoryController;

use App\Http\Controllers\Vendor\ClientController;
use App\Http\Controllers\Vendor\HomeController;
use App\Http\Controllers\Vendor\InvestorController;
use App\Http\Controllers\Vendor\OrderController;
use App\Http\Controllers\Vendor\PlanController;
use App\Http\Controllers\Vendor\RoleController;
use App\Http\Controllers\Vendor\SettingController;
use App\Http\Controllers\Vendor\StockController;
use App\Http\Controllers\Vendor\UnsurpassedController;
use App\Http\Controllers\Vendor\VendorController;
use App\Models\Vendor;
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



            Route::group(['middleware' => 'auth:vendor'], function () {
                Route::get('my_profile', [VendorController::class, 'myProfile'])->name('vendor.myProfile');
                Route::get('my_profile/edit', [VendorController::class, 'editProfile'])->name('vendor.myProfile.edit');
                Route::post('my_profile/update', [VendorController::class, 'updateProfile'])->name('vendor.myProfile.update');

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
                Route::get('/investors/stocks/summary/{id}', [InvestorController::class, 'InvestorStocksSummary'])->name('investors.stocks.summary');


                #============================ client ====================================
                Route::resourceWithDeleteSelected('clients', ClientController::class);
                Route::get('/get-user-by-national-id', [ClientController::class, 'getUserByNationalId'])->name('vendor.clients.getUserByNationalId'); //using for order

                #============================ Stocks ==================================
                Route::resourceWithDeleteSelected('stocks', StockController::class);
                Route::POST('/stocks/get-branches', [StockController::class, 'getBranches'])->name('vendor.stocks.getBranches'); //using for stock

                Route::get('unsurpasseds/download-example', [UnsurpassedController::class, 'downloadExample'])->name('unsurpasseds.download.example');

                Route::resourceWithDeleteSelected('unsurpasseds', UnsurpassedController::class);
                Route::get('unsurpasseds/add/Excel', [UnsurpassedController::class, 'addExcel'])->name('unsurpasseds.add.excel');
                Route::post('unsurpasseds/store/Excel', [UnsurpassedController::class, 'storeExcel'])->name('unsurpasseds.store.excel');


                #============================ plans ==================================

                Route::resourceWithDeleteSelected('plans', PlanController::class, [
                    'as' => 'vendor',
                ]);

                #============================ orders ==================================
                Route::resourceWithDeleteSelected('orders', OrderController::class);
                Route::get('/get-prices', [OrderController::class, 'calculatePrices'])->name('vendor.orders.calculatePrices'); //using for order
                Route::get('/edit-order-status/{id}', [OrderController::class, 'editOrderStatus'])->name('vendor.orders.editOrderStatus'); //using for order
                Route::put('/update-order-status', [OrderController::class, 'updateOrderStatus'])->name('vendor.orders.updateOrderStatus'); //using for order

                Route::POST('/orders/get-investors', [OrderController::class, 'getInvestors'])->name('vendor.orders.getInvestors'); //using for order
                Route::POST('/orders/get-categories', [OrderController::class, 'getCategories'])->name('vendor.orders.getCategories'); //using for order

            });


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
