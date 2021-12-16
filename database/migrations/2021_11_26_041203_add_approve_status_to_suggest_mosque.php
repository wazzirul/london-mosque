<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApproveStatusToSuggestMosque extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::table('suggest_mosque', function (Blueprint $table) {
            $table->dropForeign(['id_borough']);
            $table->dropForeign(['id_district']);
            $table->dropColumn('id_borough');
            $table->dropColumn('id_district');
        });
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        Schema::table('suggest_mosque', function (Blueprint $table) {
            $table->smallInteger('name_mosque_approve_status')->default('0')->after('name_mosque');
            $table->smallInteger('address_approve_status')->default('0')->after('address');
            $table->smallInteger('latitude_approve_status')->default('0')->after('latitude');
            $table->smallInteger('longitude_approve_status')->default('0')->after('longitude');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('suggest_mosque', function (Blueprint $table) {
            $table->dropColumn('status_suggest_mosque');
            $table->dropColumn('id_borough');
            $table->dropColumn('id_district');
            $table->dropColumn('name_mosque_approve_status');
            $table->dropColumn('address_approve_status');
            $table->dropColumn('latitude_approve_status');
            $table->dropColumn('longitude_approve_status');
            //
        });
    }
}
