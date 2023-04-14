<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class DefaultUsdValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_usd_value', function (Blueprint $table) {
            $table->id();
            $table->string('predefined_usd_value');
            $table->timestamps();
        });
        DB::table('default_usd_value')->insert(
            array(
                'predefined_usd_value' => '1000',
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('default_usd_value');
    }
}
