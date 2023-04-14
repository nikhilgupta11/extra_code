<?php

namespace Modules\Transportation\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Transportation\Models\Transportation;

class TransportationDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks!
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        /*
         * Transportations Seed
         * ------------------
         */

        // DB::table('transportations')->truncate();
        // echo "Truncate: transportations \n";

        Transportation::factory()->count(20)->create();
        $rows = Transportation::all();
        echo " Insert: transportations \n\n";

        // Enable foreign key checks!
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
