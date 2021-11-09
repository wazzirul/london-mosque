<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuggestMosqueSalahTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suggest_mosque_salah_times', function (Blueprint $table) {
            $table->increments('id_suggest_salah_times');
            $table->integer('id_suggest_mosque')->unsigned();
            $table->foreign('id_suggest_mosque')->references('id_suggest_mosque')->on('suggest_mosque');
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
        Schema::dropIfExists('suggest_mosque_salah_times');
    }
}
