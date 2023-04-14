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
        Schema::create('userprofiles', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('gender',15)->nullable();
            $table->string('url_website',100)->nullable();
            $table->string('url_facebook',100)->nullable();
            $table->string('url_twitter',100)->nullable();
            $table->string('url_instagram',100)->nullable();
            $table->string('url_linkedin',100)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('address')->nullable();
            $table->text('bio')->nullable();
            $table->text('user_metadata')->nullable();
            $table->string('last_ip')->nullable();
            $table->integer('login_count')->default(0);
            $table->timestamp('last_login')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->tinyInteger('status')->default(1)->unsigned();
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
        Schema::dropIfExists('userprofiles');
    }
};
