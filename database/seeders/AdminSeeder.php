<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::create([
            'name' => 'admin',
            'user_name' => 'admin',
            'phone' => '+966123456789',
            'code' => Str::random(11),
            'email' => 'admin@admin.com',
            'role_id' => 1,
            'status' => 1,
            'password' => bcrypt('admin'),
        ]);
        $ahmed = Admin::create([
            'name' => 'ahmed',
            'phone' => '+966123456787',

            'user_name' => 'ahmed',
            'code' => Str::random(11),
            'email' => 'ahmedesmaelgamal@gmail.com',
            'role_id' => 1,
            'status' => 1,

            'password' => bcrypt('admin'),
        ]);
        $mohamed = Admin::create([
            'name' => 'mohamed',
            'phone' => '+966123456782',

            'user_name' => 'mohamed',
            'code' => Str::random(11),
            'email' => 'amomatter48@gmail.com',
            'role_id' => 1,
            'status' => 1,

            'password' => bcrypt('admin'),
        ]);

        $admin->assignRole([1]);
        $ahmed->assignRole([1]);
        $mohamed->assignRole([1]);
    }

}
