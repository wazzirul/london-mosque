<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApproveStatusToSuggestMosqueLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::table('suggest_mosque_log', function (Blueprint $table) {
            $table->dropForeign(['id_suggest_mosque']);
            $table->dropColumn('id_suggest_mosque');
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        Schema::table('suggest_mosque_log', function (Blueprint $table) {
            $table->string('field_name_suggest_edit')->after('id_suggest_mosque_log');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suggest_mosque_log', function (Blueprint $table) {
            $table->dropColumn('id_suggest_mosque');
            //
        });
    }
}
