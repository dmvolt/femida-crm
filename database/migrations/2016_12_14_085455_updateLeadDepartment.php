<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLeadDepartment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->integer('user_id', false, true)->default(0)->change();
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->integer('user_id', false, true)->default(0)->change();
            $table->integer('department_id', false, true)->default(0);

            $table->index('department_id', 'dep_id');
            $table->index(['department_id', 'user_id'], 'dep_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex('dep_id');
            $table->dropIndex('dep_user');

            $table->dropColumn('department_id');

        });
    }
}
