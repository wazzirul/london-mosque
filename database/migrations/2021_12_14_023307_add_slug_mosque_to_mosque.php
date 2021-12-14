<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddSlugMosqueToMosque extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mosque', function (Blueprint $table) {
            $table->string('slug_mosque')->nullable()->after('id_district');
        });

        DB::statement('UPDATE mosque SET slug_mosque = id_mosque');

        Schema::table('mosque', function (Blueprint $table) {
            $table->string('slug_mosque')->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mosque', function (Blueprint $table) {
            //
            $table->dropColumn('slug_mosque');
        });
    }
}
