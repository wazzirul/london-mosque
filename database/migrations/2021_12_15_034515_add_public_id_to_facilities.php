<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddPublicIdToFacilities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->string('public_id')->nullable()->after('name_facilities');
        });

        DB::statement('UPDATE facilities SET public_id = id_facilities');

        Schema::table('facilities', function (Blueprint $table) {
            $table->string('public_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('facilities', function (Blueprint $table) {
            //
            $table->dropColumn('public_id');
        });
    }
}
