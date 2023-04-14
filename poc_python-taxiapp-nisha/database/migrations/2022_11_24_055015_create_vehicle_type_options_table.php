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
        Schema::create('vehicle_type_options', function (Blueprint $table) {
            $table->id();
            $table->Integer('vehicle_type');
            $table->float('per_km_price');
            $table->time('waiting_time');
            $table->float('waiting_charge');
            $table->Integer('capicity');
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
        Schema::dropIfExists('vehicle_type_options');
    }
};
