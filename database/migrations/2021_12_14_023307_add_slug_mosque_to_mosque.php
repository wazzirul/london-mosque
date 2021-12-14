<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlugMosqueToMosque extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mosque', function (Blueprint $table) {
            //
            $table->string('slug_mosque')->unique()->after('id_district');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mosque', function (Blueprint $table) {
            //
            $table->dropColumn('slug_mosque');
        });
    }
}
