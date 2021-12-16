<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddPublicIdToSuggestMosqueImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('suggest_mosque_image', function (Blueprint $table) {
            $table->string('public_id')->nullable()->after('id_suggest_mosque');
        });

        DB::statement('UPDATE suggest_mosque_image SET public_id = id_suggest_mosque_image');

        Schema::table('suggest_mosque_image', function (Blueprint $table) {
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
        Schema::table('suggest_mosque_image', function (Blueprint $table) {
            //
            $table->dropColumn('public_id');
        });
    }
}
