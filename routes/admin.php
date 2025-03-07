<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CountryController;

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\VendorController;

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
            Route::resourceWithDeleteSelected('roles', RoleController::class);
//            Route::resourceWithDeleteSelected('permissions', PermissionController::class);
            Route::get('activity_logs', [\App\Http\Controllers\Admin\ActivityLogController::class,'index'])->name('activity_logs.index');
            Route::delete('activity_logs/{id}', [\App\Http\Controllers\Admin\ActivityLogController::class,'destroy'])->name('activity_logs.destroy');
            #============================ User ====================================

            #============================ vendors ====================================
            Route::resourceWithDeleteSelected('vendors', VendorController::class);


            #============================ Admin ====================================
            Route::resourceWithDeleteSelected('admins', AdminController::class);
            #============================ contact us ==================================
            #============================ countries ==================================
            Route::resourceWithDeleteSelected('countries', CountryController::class);
            #============================ cities ==================================
            Route::resourceWithDeleteSelected('cities', CityController::class);
            #============================ Modules ==================================



            Route::get('my_profile', [AdminController::class, 'myProfile'])->name('myProfile');
            Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');



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


