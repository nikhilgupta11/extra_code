<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('name',150);
            $table->string('slug',150)->nullable();
            $table->text('intro')->nullable();
            $table->text('content')->nullable();
            $table->integer('category_id')->unsigned()->nullable();
            $table->string('category_name',30)->nullable();
            $table->string('banner')->nullable();
            $table->json('imgs_videos')->nullable();
            $table->string('meta_title',150)->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_og_image')->nullable();
            $table->string('meta_og_url')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamp('published_at')->nullable();
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
        Schema::dropIfExists('posts');
    }
}
