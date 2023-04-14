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
        Schema::create('manage_vehicle_infromations', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_type_id');
            $table->integer('driver_id');
            $table->integer('vehicle_type_option_id');
            $table->string('waiting_time');
            $table->float('waiting_charge');
            $table->string('vehicle_name');
            $table->string('vehicle_number');
             $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manage_vehicle_infromations');
    }
};
