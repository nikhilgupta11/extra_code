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
        Schema::create('driver_availibilities', function (Blueprint $table) {
            $table->id();
            $table->integer('driver_id');
            $table->string('day');
            $table->integer('status');
            $table->integer('start_time');
            $table->integer('end_time');
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
        Schema::dropIfExists('driver_availibilities');
    }
};
