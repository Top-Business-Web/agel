<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\City;
use App\Models\Country;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

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


        $vendor = Vendor::create([
            'name' => 'vendor',
            'phone' => '01000000000',
            'national_id' => '12344564645422',
            'city_id'=>1,
            'role_id'=>6,
            'status' => 1,
            'username' => 'vendor',
            'email' => 'vendor@vendor.com',
            'password' => bcrypt('vendor'),
        ]);

        Role::where('name', '=', RoleEnum::PARTNER_ADMIN->label())->first();

        $vendor->assignRole([6]);




    }

}
