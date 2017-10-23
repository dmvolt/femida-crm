<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->increments('id');
            $table->char('name');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('department_id', false, true);

            $table->index('department_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departments');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('department_id');
        });

    }
}
