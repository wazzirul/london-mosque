<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuggestMosqueFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suggest_mosque_facilities', function (Blueprint $table) {
            $table->increments('id_suggest_mosque_facilities');
            $table->integer('id_suggest_mosque')->unsigned();
            $table->foreign('id_suggest_mosque')->references('id_suggest_mosque')->on('suggest_mosque');
            $table->integer('id_facilities')->unsigned();
            $table->foreign('id_facilities')->references('id_facilities')->on('facilities');

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
        Schema::dropIfExists('suggest_mosque_facilities');
    }
}
