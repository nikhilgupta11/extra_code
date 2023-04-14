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
            $table->boolean('round_trip')->after('to')->default(0);
            $table->date('start_date')->after('round_trip')->default(now());
            $table->integer('trip_days')->after('start_date')->default(1);
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
            $table->dropColumn('round_trip');
            $table->dropColumn('start_date');
            $table->dropColumn('trip_days');
        });
    }
};
