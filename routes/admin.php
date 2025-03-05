<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\ContactUsController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\ModuleController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\RestaurantCategoryController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SuitcaseController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\LodgeController;
use App\Http\Controllers\Admin\PlaceController;
use App\Http\Controllers\Vendor\BusModelController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\RestaurantController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ], function () {

    Route::get('/', [AuthController::class, 'index']);
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/login', [AuthController::class, 'index'])->name('admin.login');

        Route::POST('login', [AuthController::class, 'login'])->name('admin.login');

        Route::group(['middleware' => 'auth:admin'], function () {
            Route::get('/', function () {
                return view('admin/index');
            })->name('adminHome');


            #============================ User ====================================
            Route::resourceWithDeleteSelected('users', UserController::class);

            #============================ vendors ====================================
            Route::resourceWithDeleteSelected('vendors', VendorController::class);

            #============================ places ====================================
            Route::resourceWithDeleteSelected('places', PlaceController::class);

            #============================ places ====================================
            Route::resourceWithDeleteSelected('restaurantCategory', RestaurantCategoryController::class);

           #============================ restaurant ====================================
            Route::resourceWithDeleteSelected('restaurant', RestaurantController::class);

            #============================ RoomReservation ====================================
            Route::resourceWithDeleteSelected('RoomReservation', \App\Http\Controllers\Admin\RoomReservationController::class);

            #============================ BussReservation ====================================
            Route::resourceWithDeleteSelected('BussReservation', \App\Http\Controllers\Admin\BusReservtionController::class);

            #============================ RestaurantReservation ====================================
            Route::resourceWithDeleteSelected('RestaurantReservation', \App\Http\Controllers\Admin\RestaurantReservationController::class);

            #============================ Admin ====================================
            Route::resourceWithDeleteSelected('admins', AdminController::class);
            #============================ contact us ==================================
            Route::resourceWithDeleteSelected('contactUs', ContactUsController::class);
            #============================ countries ==================================
            Route::resourceWithDeleteSelected('countries', CountryController::class);
            #============================ cities ==================================
            Route::resourceWithDeleteSelected('cities', CityController::class);
            #============================ Modules ==================================
            Route::resourceWithDeleteSelected('modules', ModuleController::class);
            #============================ facilities ==================================
            Route::resourceWithDeleteSelected('facilities', FacilityController::class);
            #============================ companies ==================================
            Route::resourceWithDeleteSelected('companies', CompanyController::class);
            #============================ bus models ==================================
            Route::resourceWithDeleteSelected('bus_models', BusModelController::class);
            #============================ restaurant_category ==================================

            Route::resourceWithDeleteSelected('restaurant_category',RestaurantCategoryController::class);
            Route::resourceWithDeleteSelected('restaurant',RestaurantController::class);


            Route::get('my_profile', [AdminController::class, 'myProfile'])->name('myProfile');
            Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');
            #============================ offers ====================================
            Route::resourceWithDeleteSelected('offers', OfferController::class);
            #============================ lodges ====================================
            Route::resourceWithDeleteSelected('lodges', LodgeController::class);
            #============================ suitcases ====================================
            Route::resourceWithDeleteSelected('suitcasesAdmin', SuitcaseController::class);
            #============================ Coupons ====================================
            Route::resourceWithDeleteSelected('coupons', CouponController::class);
            #============================ Setting ==================================


            Route::get('setting', [SettingController::class, 'index'])->name('settingIndex');
            Route::POST('setting/store', [SettingController::class, 'store'])->name('setting.store');
            Route::POST('setting/update/{id}/', [SettingController::class, 'update'])->name('settingUpdate');

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
});
