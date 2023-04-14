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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->nullable()->references('id')->on('trips')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->references('id')->on('users')->nullOnDelete();
            $table->foreignId('project_id')->nullable()->references('id')->on('projects')->nullOnDelete();
            $table->string('order_id');
            $table->string('payment_id')->nullable();
            $table->string('first_name', 70);
            $table->string('last_name', 70);
            $table->string('country');
            $table->string('state')->nullable();
            $table->string('city');
            $table->string('street');
            $table->string('zip', 6);
            $table->string('card_holder', 70)->nullable();
            $table->string('card_number', 18)->nullable();
            $table->date('expiry')->nullable();
            $table->string('card_code', 4)->nullable();
            $table->string('discount_coupon')->nullable();
            $table->float('discount')->default(0.00);
            $table->float('amount')->default(0.00);
            $table->string('payment_status')->default('pending'); //pending,success,failed
            $table->string('certificate')->nullable();
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
        Schema::dropIfExists('payments');
    }
};
