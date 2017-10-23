<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLeadStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lead_statuses', function (Blueprint $table) {
            $table->enum('type', \App\LeadStatus::$types)->default('normal');
            $table->enum('color', \App\LeadStatus::$colors)->default('success');
            $table->dropColumn('default');

            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lead_statuses', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->enum('default', ['yes', 'no'])->default('no');
        });
    }
}
