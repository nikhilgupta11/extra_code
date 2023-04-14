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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('is_email_verify');
            $table->string('user_type'); // new column
            $table->string('password');
            $table->string('mobile_number');
            $table->string('gender');
            $table->integer('otp');
            $table->string('slug');
            $table->string('vehicle_number');
            $table->string('license_number');
            $table->string('insurance_document');
            $table->string('license_document');
            $table->string('vehicle_rc');
            $table->integer('bank_account_number');
            $table->string('ifsc_code');
            $table->string('bank_account_name');
            $table->string('bank_name');
            $table->string('bank_branch_name');
            $table->text('bank_branch_address');
            $table->float('wallet_balance');
            $table->integer('is_available');
            $table->string('latitude');
            $table->string('longitude');
            $table->rememberToken();
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
        Schema::dropIfExists('drivers');
    }
};
