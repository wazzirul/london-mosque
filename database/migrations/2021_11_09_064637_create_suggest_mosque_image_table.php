<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuggestMosqueImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suggest_mosque_image', function (Blueprint $table) {
            $table->increments('id_suggest_mosque_image');
            $table->integer('id_suggest_mosque')->unsigned();
            $table->foreign('id_suggest_mosque')->references('id_suggest_mosque')->on('suggest_mosque');
            $table->string('mosque_image_url');

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
        Schema::dropIfExists('suggest_mosque_image');
    }
}
