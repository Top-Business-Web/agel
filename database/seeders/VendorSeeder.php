<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Branch;
use App\Models\Vendor;
use App\Models\VendorBranch;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
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

            'city_id' => 1,
            'role_id' => 6,
            'status' => 1,
            'username' => 'vendor',
            'email' => 'vendor@vendor.com',
            'password' => bcrypt('vendor'),
        ]);


        // create main branch
        $b1 = Branch::create([
            'name' => 'الفرع الرئيسي',
            'is_main' => true,
            'vendor_id' => $vendor->id

        ]);

        VendorBranch::create([
            'vendor_id' => $vendor->id,
            'branch_id' => $b1->id
        ]);


        $ahmed = Vendor::create([
            'name' => 'ahmed',
            'phone' => '+9660111111111',
            'national_id' => '0000000001',
            'commercial_number' => '12341214564645',
            'plan_id' => 1,
            'city_id' => 1,
            'role_id' => 6,
            'status' => 1,
            'username' => 'ahmed',
            'email' => 'ahmedesmaelgamal@gmail.com',
            'password' => bcrypt('vendor'),
        ]);


        // create main branch
        $b2 = Branch::create([
            'name' => 'الفرع الرئيسي',
            'is_main' => true,
            'vendor_id' => $ahmed->id

        ]);

        VendorBranch::create([
            'vendor_id' => $ahmed->id,
            'branch_id' => $b2->id
        ]);


        $mohamed = Vendor::create([
            'name' => 'amo',
            'phone' => '+9660001111111',
            'national_id' => '0000000011',
            'commercial_number' => '12344121564645',
            'plan_id' => 1,
            'city_id' => 1,

            'role_id' => 6,
            'status' => 1,
            'username' => 'amo',
            'email' => 'amomatter48@gmail.com',
            'password' => bcrypt('vendor'),
        ]);

        // create main branch
        $b3 = Branch::create([
            'name' => 'الفرع الرئيسي',
            'is_main' => true,
            'vendor_id' => $mohamed->id

        ]);

        VendorBranch::create([
            'vendor_id' => $mohamed->id,
            'branch_id' => $b3->id,
        ]);


       $permissions=Permission::where('guard_name','vendor')->get();
       $vendor->syncPermissions($permissions);
       $ahmed->syncPermissions($permissions);
       $mohamed->syncPermissions($permissions);
    }
}
