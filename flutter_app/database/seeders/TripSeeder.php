<?php

namespace Database\Seeders;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Transportation\Models\Transportation;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        
        foreach (range(1,20) as $key => $value) {
            $user = User::where('type',1)->inRandomOrder()->select('id')->first();
            $transports = Transportation::inRandomOrder()->select('id')->take(2)->pluck('id')->toArray();
            $trip = Trip::create([
                'user_id' => $user->id,
                'from' => $faker->city,
                'to' => $faker->city,
                'peoples' => $faker->numberBetween(1,10)
            ]);
            $trip->transports()->sync($transports);
        }
    }
}
