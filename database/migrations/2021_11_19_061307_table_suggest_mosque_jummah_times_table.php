<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableSuggestMosqueJummahTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suggest_mosque_special_salah_times', function (Blueprint $table) {
            $table->renameColumn('id_jummah_times', 'id_special_salah_times');
            $table->renameColumn('date_jummah_times', 'date_special_salah_times');
            $table->renameColumn('order_jummah_times', 'order_special_salah_times');
            $table->renameColumn('time_jummah_times', 'time_special_salah_times');

            $table->smallInteger('category_special_salah');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
