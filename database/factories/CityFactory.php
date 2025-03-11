<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create('ar_SA');
        return [
            'name' => $faker->city(),
            'status' => $this->faker->boolean,
            'country_id' => $this->faker->numberBetween(1,10),
        ];
    }


}
