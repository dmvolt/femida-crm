<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_services', function (Blueprint $table) {
            $table->increments('id');
            $table->char('name');
            $table->float('cost');

            $table->timestamps();
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->integer('service_id', false, true);

            $table->index('service_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads');
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn('service_id');
        });

    }
}
