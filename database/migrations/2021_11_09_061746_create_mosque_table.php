<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMosqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mosque', function (Blueprint $table) {
            $table->increments('id_mosque');
            $table->string('id_mosque_google_place')->unique();
            $table->integer('id_borough')->unsigned();
            $table->foreign('id_borough')->references('id_borough')->on('borough');
            $table->integer('id_district')->unsigned();
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
        Schema::dropIfExists('mosque');
    }
}
