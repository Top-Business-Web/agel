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
            'code' => Str::random(11),
            'email' => 'admin@admin.com',
            'role_id' => 1,
            'password' => bcrypt('admin'),
        ]);

        Role::where('name', '=', RoleEnum::SUPER_ADMIN->label())->first();
        $admin->assignRole([1]);
    }

}
