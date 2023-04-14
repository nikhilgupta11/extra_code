<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Accommodation\database\seeders\AccommodationDatabaseSeeder;

class TripAllSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('trips')->truncate();
        DB::table('trip_calculations')->truncate();
        DB::table('trip_transportation')->truncate();
        DB::table('accommodations')->truncate();
        $this->call(TripSeeder::class);
        $this->call(CalculationSeeder::class);
        $this->call(AccommodationDatabaseSeeder::class);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
