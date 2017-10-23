<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_activities', function (Blueprint $table) {
            $table->increments('id');

            $table->enum('type', \App\LeadActivity::TYPES);
            $table->text('text');

            $table->integer('lead_id', false, true);
            $table->index('lead_id');

            $table->integer('user_id', false, true);
            $table->index('user_id');

            $table->index(['user_id', 'lead_id']);
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
        Schema::dropIfExists('lead_activities');
    }
}
