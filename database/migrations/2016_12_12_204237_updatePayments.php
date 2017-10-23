<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_payments', function (Blueprint $table) {
            $table->dropColumn('bonus');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->float('bonus', false, true)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('service_payments', function (Blueprint $table) {
            $table->integer('bonus', false, true);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('bonus');
        });

    }
}
