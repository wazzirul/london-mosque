<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApproveStatusToSuggestMosqueImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suggest_mosque_image', function (Blueprint $table) {
            $table->smallInteger('mosque_image_approve_status')->default('0')->after('mosque_image_url');
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
        Schema::table('suggest_mosque_image', function (Blueprint $table) {
            $table->dropColumn('mosque_image_approve_status');
            //
        });
    }
}
