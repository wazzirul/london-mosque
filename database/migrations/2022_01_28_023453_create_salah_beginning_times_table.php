<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalahBeginningTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salah_beginning_times', function (Blueprint $table) {
            $table->increments('id_salah_beginning_times');
            $table->date('date_salah_beginning_times');
            $table->smallInteger('type_salah_beginning_times');
            $table->time('time_salah_beginning_times');

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
        Schema::dropIfExists('salah_beginning_times');
    }
}
