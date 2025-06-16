<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportEopProjectTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_eop_project_team', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_end_project_id')->index('report_end_project_id_index');
            $table->integer('type');
            $table->string('name');
            $table->string('organization');
            $table->string('man_month');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report_eop_project_team');
    }
}
