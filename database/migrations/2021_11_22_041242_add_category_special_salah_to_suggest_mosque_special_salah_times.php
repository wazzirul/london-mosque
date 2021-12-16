<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategorySpecialSalahToSuggestMosqueSpecialSalahTimes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        Schema::table('suggest_mosque_special_salah_times', function (Blueprint $table) {
            $table->dropColumn('category_special_salah');
            //
        });

        Schema::table('suggest_mosque_special_salah_times', function (Blueprint $table) {
            $table->smallInteger('category_special_salah')->after('id_suggest_mosque');
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suggest_mosque_special_salah_times', function (Blueprint $table) {
            $table->dropColumn('category_special_salah');
            //
        });
    }
}
