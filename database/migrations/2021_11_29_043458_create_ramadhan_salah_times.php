<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRamadhanSalahTimes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ramadhan_salah_times', function (Blueprint $table) {
            $table->increments('id_ramadhan_salah_times');
            $table->date('date_ramadhan_salah_times');
            $table->smallInteger('type_ramadhan_salah_times');
            $table->time('time_ramadhan_salah_times');

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
        Schema::dropIfExists('ramadhan_salah_times');
    }
}
