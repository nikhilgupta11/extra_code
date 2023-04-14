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
        Schema::create('rider_reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('ride_id');
            $table->integer('review_by');
            $table->integer('review_to');
            $table->integer('rating');
            $table->string('descrition');
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
        Schema::dropIfExists('rider_reviews');
    }
};
