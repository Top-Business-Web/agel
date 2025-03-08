<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\PlanController;

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
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
    ],
    function () {

        Route::get('/', [AuthController::class, 'index']);
        Route::group(['prefix' => 'admin'], function () {
            Route::get('/login', [AuthController::class, 'index'])->name('admin.login');

            Route::POST('login', [AuthController::class, 'login'])->name('admin.login');

            Route::group(['middleware' => 'auth:admin'], function () {
                Route::get('/', function () {
                    return view('admin/index');
                })->name('adminHome');

                Route::group(['middleware' => 'auth:admin'], function () {
                    Route::get('/', function () {
                        return view('admin/index');
                    })->name('adminHome');


                    Route::resourceWithDeleteSelected('roles', RoleController::class, [
                        'as' => 'admin'  // Prefix "admin." to all route names
                    ]);
                    Route::get('activity_logs', [ActivityLogController::class, 'index'])->name('admin.activity_logs.index');
                    Route::delete('activity_logs/{id}', [ActivityLogController::class, 'destroy'])->name('admin.activity_logs.destroy');
                    #============================ User ====================================

                    #============================ User ====================================

                    #============================ vendors ====================================
//                    Route::resourceWithDeleteSelected('vendors', VendorController::class);
                    Route::get('vendors/index', [VendorController::class, 'index'])->name('admin.vendors.index');
                    Route::get('vendors/create', [VendorController::class, 'create'])->name('admin.vendors.create');
                    Route::post('vendors', [VendorController::class, 'store'])->name('admin.vendors.store');
                    Route::put('vendors/update', [VendorController::class, 'update'])->name('admin.vendors.update');
                    Route::delete('vendors/{id}', [VendorController::class, 'destroy'])->name('admin.vendors.destroy');
                    Route::get('vendors/{id}/edit', [VendorController::class, 'edit'])->name('admin.vendors.edit');
                    Route::get('vendors/delete-selected', [VendorController::class, 'deleteSelected'])->name('admin.vendors.deleteSelected');
                    Route::post('vendors/update-column-selected', [VendorController::class, 'updateColumnSelected'])->name('admin.vendors.updateColumnSelected');
                    #============================ Admin ====================================
                    Route::resourceWithDeleteSelected('admins', AdminController::class);
                    #============================ countries ==================================
                    Route::resourceWithDeleteSelected('countries', CountryController::class);
                    #============================ cities ==================================
                    Route::resourceWithDeleteSelected('cities', CityController::class);
                    #============================ branches ==================================
                    Route::resourceWithDeleteSelected('branches', BranchController::class);
                    #============================ Plans ==================================
                    Route::resourceWithDeleteSelected('Plans', PlanController::class);


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

        }
        );
    }
);



Route::resourceWithDeleteSelected('plan_subscriptions', \App\Http\Controllers\Admin\PlanSubscriptionController::class);

Route::resourceWithDeleteSelected('investors', \App\Http\Controllers\Admin\InvestorController::class);

Route::resourceWithDeleteSelected('clients', \App\Http\Controllers\Admin\ClientController::class);
