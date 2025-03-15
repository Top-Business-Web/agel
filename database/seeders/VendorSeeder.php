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
            'phone' => '0000001000',
            'national_id' => '1234456412164',
            'commercial_number' => '12312144564645',
            'region_id' => 1,
            'role_id' => 6,
            'status' => 1,
            'username' => 'vendor',
            'email' => 'vendor@vendor.com',
            'password' => bcrypt('vendor'),
        ]);
        $ahmed = Vendor::create([
            'name' => 'vendor',
            'phone' => '00210000000',
            'national_id' => '1234451216464',
            'commercial_number' => '12341214564645',
            'region_id' => 1,
            'role_id' => 6,
            'status' => 1,
            'username' => 'vendgdfor',
            'email' => 'ahmedesmaelgamal@gmail.com',
            'password' => bcrypt('vendor'),
        ]);
        $mohamed = Vendor::create([
            'name' => 'vendor2',
            'phone' => '00000012000',
            'national_id' => '1234456422164',
            'commercial_number' => '12344121564645',
            'region_id' => 1,
            'role_id' => 6,
            'status' => 1,
            'username' => 'vendgor',
            'email' => 'amomatter48@gmail.com',
            'password' => bcrypt('vendor'),
        ]);
        Role::where('name', '=', RoleEnum::PARTNER_ADMIN->label())->first();

        $vendor->assignRole([6]);
        $ahmed->assignRole([6]);
        $mohamed->assignRole([6]);


    }

}
