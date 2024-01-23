<?php

use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = ["Lucknow", "Delhi", "Agra", "Noida"];

        foreach ($cities as $city){
            \App\Models\Cities::create(['city_name' => $city]);
        }
    }
}
