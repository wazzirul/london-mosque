<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMosqueFavoriteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mosque_favorite', function (Blueprint $table) {
            $table->increments('id_mosque_favorite');
            $table->integer('id_mosque')->unsigned();
            $table->foreign('id_mosque')->references('id_mosque')->on('mosque');
            $table->integer('id_user')->unsigned();
            $table->foreign('id_user')->references('id_user')->on('users');

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
        Schema::dropIfExists('mosque_favorite');
    }
}
