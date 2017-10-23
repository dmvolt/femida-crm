<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task_id', false, true);
            $table->text('text');
            $table->enum('created', ['no', 'yes'])->default('no');
            $table->timestamp('datetime');
            $table->index('task_id');

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
        Schema::dropIfExists('task_notifications');
    }
}
