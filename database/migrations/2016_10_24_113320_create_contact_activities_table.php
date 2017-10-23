<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_activities', function (Blueprint $table) {
            $table->increments('id');

            $table->enum('type', \App\ContactActivity::TYPES);
            $table->text('text');

            $table->integer('contact_id', false, true);
            $table->index('contact_id');

            $table->integer('user_id', false, true);
            $table->index('user_id');

            $table->index(['user_id', 'contact_id']);
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
        Schema::dropIfExists('contact_activities');
    }
}
