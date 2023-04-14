<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->boolean('trip_history')->default(0)->after('peoples');
        });
        Schema::table('trip_calculations', function (Blueprint $table) {
            $table->decimal('total_co2_per_person')->default(0.00)->after('accommodation_emission_total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn('trip_history');
        });
        Schema::table('trip_calculations', function (Blueprint $table) {
            $table->dropColumn('total_co2_per_person');
        });
    }
};
