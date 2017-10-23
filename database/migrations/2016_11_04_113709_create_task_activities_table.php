<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_activities', function (Blueprint $table) {
            $table->increments('id');

            $table->enum('type', \App\TaskActivity::TYPES);
            $table->text('text');

            $table->integer('task_id', false, true);
            $table->index('task_id');

            $table->integer('lead_id', false, true)->default(0);
            $table->index('lead_id');

            $table->integer('user_id', false, true);
            $table->index('user_id');

            $table->index(['user_id', 'lead_id', 'task_id']);
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
        Schema::dropIfExists('task_activities');
    }
}
