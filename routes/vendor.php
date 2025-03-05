<?php

use App\Http\Controllers\Vendor\AuthController;
use App\Http\Controllers\Vendor\RoomController;
use App\Http\Controllers\Vendor\BusChairController;
use App\Http\Controllers\Vendor\BusController;
use App\Http\Controllers\Vendor\BusTimeController;
use App\Http\Controllers\Vendor\CompanySituationController;
use App\Http\Controllers\Vendor\SuitcaseController;
use App\Http\Controllers\Vendor\HomeController;
use App\Http\Controllers\Vendor\LodgeRuleController;
use App\Http\Controllers\Vendor\OfferController;
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

                Route::get('/lodgeSelected/{id}', [LodgeRuleController::class, 'getLodge'])->name('lodges.get');


                #============================ Home ====================================
                Route::get('homeVendor', [HomeController::class, 'index'])->name('vendorHome');
                #============================ offer ====================================
                Route::resourceWithDeleteSelected('offersVendor', OfferController::class);
                #============================ offer ====================================
                Route::resourceWithDeleteSelected('suitcaseVendor', SuitcaseController::class);
                #============================ restaurants ====================================
                Route::resourceWithDeleteSelected('vendorRestaurants', \App\Http\Controllers\Vendor\RestaurantVendorController::class);
                Route::get('RestaurantTimeShow/{id}', [\App\Http\Controllers\Vendor\RestaurantVendorController::class, 'RestaurantTimeShow'])->name('RestaurantTimeShow');
                Route::post('RestaurantTimeStore/{bus_id}', [\App\Http\Controllers\Vendor\RestaurantVendorController::class, 'RestaurantTimeStore'])->name('RestaurantTimeStore');

                #============================ restaurantVendorReservation ====================================
                Route::resourceWithDeleteSelected('restaurantVendorReservation', \App\Http\Controllers\Vendor\RestaurantReservationController::class);

                #============================ restaurantVendorReservation ====================================
                Route::resourceWithDeleteSelected('VendorRoomReservation', \App\Http\Controllers\Vendor\RoomReservationController::class);


                #============================ BussVendorReservation ====================================
                Route::resourceWithDeleteSelected('VendorBussReservation', \App\Http\Controllers\Vendor\BusReservationController::class);

                #============================ restaurant menu ====================================
                Route::resourceWithDeleteSelected('menuRestaurantVendor', \App\Http\Controllers\Vendor\MenuVendorController::class);
                #============================ offer ====================================
                Route::get('lodgeRules', [LodgeRuleController::class, 'index'])->name('lodgeRules.index');
                Route::any('lodgeRulesUpdate/{id}', [LodgeRuleController::class, 'update'])->name('lodgeRulesUpdate');
                #============================ company situations ====================================
                Route::resourceWithDeleteSelected('company_situations', CompanySituationController::class);
                #============================ Buses ====================================
                Route::resourceWithDeleteSelected('buses', BusController::class);
                Route::get('busTime/{id}', [BusController::class, 'busTimeShow'])->name('busTimeShow');
                Route::post('busTimeStore/{bus_id}', [BusController::class, 'busTimeStore'])->name('busTimeStore');
                 Route::resourceWithDeleteSelected('busTime', BusTimeController::class);
                 Route::get('company_situation', [BusController::class, 'company_situation'])->name('company_situation');
                #============================ Bus chairs ====================================
                Route::resourceWithDeleteSelected('bus_chairs', BusChairController::class);
                #============================ room ====================================
                Route::resourceWithDeleteSelected('rooms', RoomController::class);
                Route::get('room/recommend', [RoomController::class, 'recommend'])->name('room.recommend');
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
