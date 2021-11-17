<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusMosqueFavoriteToMosqueFavorite extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mosque_favorite', function (Blueprint $table) {
            //
            $table->integer('status_mosque_favorite')->after('id_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mosque_favorite', function (Blueprint $table) {
            //
            $table->dropColumn('status_mosque_favorite');
        });
    }
}
