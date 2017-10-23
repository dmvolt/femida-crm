<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserData extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->char('number')->nullable();
            $table->char('code')->nullable();
            $table->text('issued')->nullable();
            $table->text('address')->nullable();
            $table->date('date')->nullable();
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
            $table->dropColumn('number');
            $table->dropColumn('code');
            $table->dropColumn('issued');
            $table->dropColumn('address');
            $table->dropColumn('date');
        });
    }
}
