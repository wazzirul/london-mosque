<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableMosque extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        
        if (Schema::hasColumn('mosque', 'slug_mosque'))
        {
            Schema::table('mosque', function (Blueprint $table)
            {
                $table->dropColumn('slug_mosque');
            });
        }
        Schema::table('mosque', function (Blueprint $table) {
            //
            $table->string('slug_mosque')->nullable()->unique()->after('id_district');
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
