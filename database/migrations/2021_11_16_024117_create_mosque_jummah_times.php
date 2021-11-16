<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMosqueJummahTimes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mosque_jummah_times', function (Blueprint $table) {
            $table->increments('id_jummah_times');
            $table->integer('id_mosque')->unsigned();
            $table->foreign('id_mosque')->references('id_mosque')->on('mosque');
            $table->date('date_jummah_times');
            $table->string('order_jummah_times');
            $table->time('time_jummah_times');

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
        Schema::dropIfExists('mosque_jummah_times');
    }
}
