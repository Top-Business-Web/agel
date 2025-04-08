<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
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
            'phone' => '+9660011111111',
            'national_id' => '0000000000',
            'commercial_number' => '12312144564645',
           'plan_id' => 1,
 
            'city_id'=> 1,
            'role_id' => 6,
            'status' => 1,
            'username' => 'vendor',
            'email' => 'vendor@vendor.com',
            'password' => bcrypt('vendor'),
        ]);
        $ahmed = Vendor::create([
            'name' => 'ahmed',
            'phone' => '+9660111111111',
            'national_id' => '0000000001',
            'commercial_number' => '12341214564645',
           'plan_id' => 1,
        'city_id'=> 1,
            'role_id' => 6,
            'status' => 1,
            'username' => 'ahmed',
            'email' => 'ahmedesmaelgamal@gmail.com',
            'password' => bcrypt('vendor'),
        ]);
        $mohamed = Vendor::create([
            'name' => 'amo',
            'phone' => '+9660001111111',
            'national_id' => '0000000011',
            'commercial_number' => '12344121564645',
           'plan_id' => 1,
            'city_id'=> 1,

            'role_id' => 6,
            'status' => 1,
            'username' => 'amo',
            'email' => 'amomatter48@gmail.com',
            'password' => bcrypt('vendor'),
        ]);
        Role::where('name', '=', RoleEnum::PARTNER_ADMIN->label())->first();

        $vendor->assignRole([6]);
        $ahmed->assignRole([6]);
        $mohamed->assignRole([6]);


    }

}
