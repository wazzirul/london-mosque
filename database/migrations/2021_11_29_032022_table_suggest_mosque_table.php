<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableSuggestMosqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (Schema::hasColumn('suggest_mosque', 'status_suggest_mosque'))
        {
            Schema::table('suggest_mosque', function (Blueprint $table) {
                $table->dropColumn('status_suggest_mosque');
                //
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('suggest_mosque', function (Blueprint $table) {
            $table->dropColumn('status_suggest_mosque');
            //
        });
    }
}
