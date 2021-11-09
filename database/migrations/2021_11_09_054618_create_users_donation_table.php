<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersDonationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_donation', function (Blueprint $table) {
            $table->increments('id_user_donation');
            $table->integer('id_user')->unsigned();
            $table->foreign('id_user')->references('id_user')->on('users');
            $table->smallInteger('type_donation');
            $table->smallInteger('repeated_donation');
            $table->smallInteger('anonymous_donation');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email_address');
            $table->string('mobile_number');
            $table->string('card_name')->nullable();
            $table->string('cvc')->nullable();
            $table->string('cardholder_name')->nullable();
            $table->dateTime('exp')->nullable();

            $table->smallInteger('soft_delete')->default(0);
            $table->timestamps(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_donation');
    }
}
