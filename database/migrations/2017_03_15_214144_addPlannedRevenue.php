<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPlannedRevenue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('revenue', false, true);
        });
        Schema::table('teams', function (Blueprint $table) {
            $table->integer('revenue', false, true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('revenue', false, true);
        });
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn('revenue', false, true);
        });
    }
}
