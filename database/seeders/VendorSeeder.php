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
            'phone' => '000000000',
            'national_id' => '1234456464',
            'commercial_number' => '12344564645',
            'region_id' => 1,
            'role_id' => 6,
            'status' => 1,
            'username' => 'vendor',
            'email' => 'vendor@vendor.com',
            'password' => bcrypt('vendor'),
        ]);
        $ahmed = Vendor::create([
            'name' => 'vendor',
            'phone' => '000000000',
            'national_id' => '1234456464',
            'commercial_number' => '12344564645',
            'region_id' => 1,
            'role_id' => 6,
            'status' => 1,
            'username' => 'vendor',
            'email' => 'ahmedesmaelgamal@gmail.com',
            'password' => bcrypt('vendor'),
        ]);
        $mohamed = Vendor::create([
            'name' => 'vendor',
            'phone' => '000000000',
            'national_id' => '1234456464',
            'commercial_number' => '12344564645',
            'region_id' => 1,
            'role_id' => 6,
            'status' => 1,
            'username' => 'vendor',
            'email' => 'amomatter48@gmail.com',
            'password' => bcrypt('vendor'),
        ]);
        Role::where('name', '=', RoleEnum::PARTNER_ADMIN->label())->first();

        $vendor->assignRole([6]);
        $ahmed->assignRole([6]);
        $mohamed->assignRole([6]);


    }

}
