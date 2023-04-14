<?php

namespace Database\Seeders;

use App\Models\TripCalculation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CalculationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $faker = \Faker\Factory::create();

        foreach (range(2,21) as $key => $value) {
            $trans = $faker->numberBetween(1,10);
            $accommo = $faker->numberBetween(1,10);
            $total = $trans + $accommo;
            TripCalculation::create([
                'trip_id' => $value,
                'transport_emission_total' => $trans,
                'accommodation_emission_total' => $accommo,
                'total_emission' => $total
            ]);
        }
    }
}
