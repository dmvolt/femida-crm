<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_payments', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('service_id', false, true);

            $table->integer('cost', false, true);
            $table->integer('bonus', false, true);
            $table->integer('days', false, true);

            $table->index('service_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_payments');
    }
}
