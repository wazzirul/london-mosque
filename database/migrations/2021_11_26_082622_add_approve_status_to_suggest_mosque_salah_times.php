<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApproveStatusToSuggestMosqueSalahTimes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suggest_mosque_salah_times', function (Blueprint $table) {
            $table->smallInteger('salah_times_approve_status')->default('0')->after('time_salah_times');
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
        Schema::table('suggest_mosque_salah_times', function (Blueprint $table) {
            $table->dropColumn('salah_times_approve_status');
            //
        });
    }
}
