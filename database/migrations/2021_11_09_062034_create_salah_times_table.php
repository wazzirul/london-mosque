<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalahTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salah_times', function (Blueprint $table) {
            $table->increments('id_salah_times');
            $table->integer('id_mosque')->unsigned();
            $table->foreign('id_mosque')->references('id_mosque')->on('mosque');
            $table->date('date_salah_times');
            $table->smallInteger('type_salah_times');
            $table->time('time_salah_times');

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
        Schema::dropIfExists('salah_times');
    }
}
