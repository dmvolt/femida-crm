<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_data', function (Blueprint $table) {
            $table->increments('id');

            $table->char('number')->nullable();
            $table->char('code')->nullable();
            $table->text('issued')->nullable();
            $table->text('address')->nullable();
            $table->date('date')->nullable();

            $table->integer('contact_id', false, true);
            $table->index('contact_id');

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
        Schema::dropIfExists('contact_data');
    }
}
