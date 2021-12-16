<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddPublicIdToMosqueImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mosque_image', function (Blueprint $table) {
            $table->string('public_id')->nullable()->after('id_mosque');
        });

        DB::statement('UPDATE mosque_image SET public_id = id_mosque_image');

        Schema::table('mosque_image', function (Blueprint $table) {
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
        Schema::table('mosque_image', function (Blueprint $table) {
            //
            $table->dropColumn('public_id');
        });
    }
}
