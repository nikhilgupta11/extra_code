<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();

            $table->string('name',100);
            $table->string('slug',100)->nullable();
            $table->string('group_name',50)->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();

            $table->tinyInteger('status')->default(1);

            $table->string('meta_title',150)->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keyword')->nullable();

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
        Schema::dropIfExists('tags');
    }
}
