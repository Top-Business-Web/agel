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
        $vendor = Vendor::create([
            'name' => 'vendor',
            'phone' => '01000000000',
            'national_id' => '12344564645422',
            'commercial_number' => '123445646454222',
            'city_id' => 1,
            'role_id' => 6,
            'status' => 1,
            'username' => 'vendor',
            'email' => 'vendor@vendor.com',
            'password' => bcrypt('vendor'),
        ]);

        Role::where('name', '=', RoleEnum::PARTNER_ADMIN->label())->first();

        $vendor->assignRole([6]);


    }

}
