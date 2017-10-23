<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateExpenseFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->integer('department_id', false, true);
            $table->index('department_id', 'dep');
            $table->index(['department_id', 'updated_at'], 'dep_date');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropIndex('dep');
            $table->dropIndex('dep_date');

            $table->dropColumn('department_id');
        });
    }
}
