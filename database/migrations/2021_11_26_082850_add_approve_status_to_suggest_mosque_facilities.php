<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApproveStatusToSuggestMosqueFacilities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suggest_mosque_facilities', function (Blueprint $table) {
            $table->smallInteger('mosque_facilities_approve_status')->default('0')->after('id_facilities');
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
        Schema::table('suggest_mosque_facilities', function (Blueprint $table) {
            $table->dropColumn('mosque_facilities_approve_status');
            //
        });
    }
}
