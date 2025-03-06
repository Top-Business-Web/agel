<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()

    {
        $country=Country::create([
            'name'=>'egypt',
'status'=>'1'
        ]);
        $city=City::create([
            'name'=>'cairo',
'status'=>'1',
'country_id'=>'1'
        ]);


        $admin = Vendor::create([
            'name' => 'vendor',
            'phone' => '01000000000',
            'national_id' => '12344564645422',
            'city_id'=>1,
            'status' => 1,
            'username' => 'vendor',
            'email' => 'vendor@vendor.com',
            'password' => bcrypt('vendor'),
        ]);




    }

}
