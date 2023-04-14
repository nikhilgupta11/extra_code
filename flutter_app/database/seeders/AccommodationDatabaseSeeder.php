<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Accommodation\Models\Accommodation;


class AccommodationDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks!

        /*
         * Accommodations Seed
         * ------------------
         */

        // DB::table('accommodations')->truncate();
        // echo "Truncate: accommodations \n";

        Accommodation::factory()->count(20)->create();
        $rows = Accommodation::all();
        echo " Insert: accommodations \n\n";

        // Enable foreign key checks!
    }
}
