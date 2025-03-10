<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Plan;
use App\Models\PlanDetail;
use Database\Factories\CountryFactory;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::factory()->count(10)->create();
    }
}
