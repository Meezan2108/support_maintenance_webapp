<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProjectFieldsToReportResearchProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('report_research_progress', function (Blueprint $table) {
            $table->string("project_title")->nullable();
            $table->dateTime("start_date")->nullable();
            $table->dateTime("end_date")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('report_research_progress', function (Blueprint $table) {
            $table->dropColumn("project_title");
            $table->dropColumn("start_date");
            $table->dropColumn("end_date");
        });
    }
}
