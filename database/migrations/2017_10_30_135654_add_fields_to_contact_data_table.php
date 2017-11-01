<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToContactDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contact_data', function (Blueprint $table) {
            $table->integer('credit_sum');
			$table->string('credit_target');
			$table->integer('is_pledge');
			$table->integer('is_guarantor');
			$table->integer('is_reference');
			$table->integer('is_delay');
			$table->string('contact_birth');
			$table->string('contact_address');
			$table->string('contact_inn');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contact_data', function (Blueprint $table) {
			$table->dropColumn('credit_sum');
			$table->dropColumn('credit_target');
			$table->dropColumn('is_pledge');
			$table->dropColumn('is_guarantor');
			$table->dropColumn('is_reference');
			$table->dropColumn('is_delay');
			$table->dropColumn('contact_birth');
			$table->dropColumn('contact_address');
			$table->dropColumn('contact_inn');
        });
    }
}
