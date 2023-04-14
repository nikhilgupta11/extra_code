<?php

namespace Modules\Project\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Project\Models\Project;


class ProjectDatabaseSeeder extends Seeder
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
         * Projects Seed
         * ------------------
         */

        // DB::table('projects')->truncate();
        // echo "Truncate: projects \n";

        Project::factory()->count(20)->create();
        $rows = Project::all();
        echo " Insert: projects \n\n";

        // Enable foreign key checks!
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
