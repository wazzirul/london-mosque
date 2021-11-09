<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuggestMosqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suggest_mosque', function (Blueprint $table) {
            $table->increments('id_suggest_mosque');
            $table->integer('id_user')->unsigned();
            $table->foreign('id_user')->references('id_user')->on('users');
            $table->smallInteger('status_suggest_mosque');
            $table->integer('id_mosque')->unsigned();
            $table->foreign('id_mosque')->references('id_mosque')->on('mosque');
            $table->integer('id_borough')->unsigned()->nullable();
            $table->foreign('id_borough')->references('id_borough')->on('borough');
            $table->integer('id_district')->unsigned()->nullable();
            $table->foreign('id_district')->references('id_district')->on('districts');
            $table->string('name_mosque');
            $table->string('address');
            $table->string('latitude');
            $table->string('longitude');

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
        Schema::dropIfExists('suggest_mosque');
    }
}
