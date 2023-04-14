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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('slug',100)->nullable();
            $table->string('banner');
            $table->string('sku',20);
            $table->float('price')->default(0.00);
            $table->float('sale_price')->nullable();
            $table->integer('stock')->default(0);
            $table->integer('threshold')->nullable();
            $table->integer('category_id')->unsigned()->nullable();
            $table->string('category_name')->nullable();
            $table->string('certification_start_txt',20)->nullable();
            $table->string('certification_end_txt',20)->nullable();
            $table->integer('certification_start_number')->nullable();
            $table->integer('certification_end_number')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
};
